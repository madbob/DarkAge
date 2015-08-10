<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use Redirect;
use Zipper;

use App\Subscriber;
use App\Set;
use App\Photo;

class HomeController extends Controller
{
	public function index()
	{
		$data['subscribers'] = Subscriber::orderBy('created_at', 'desc')->get();
		return view('home', $data);
	}

	function assembleUrl($params)
	{
		$params['api_key'] = env('FLICKR_API_KEY');
		$params['format'] = 'json';
		$params['nojsoncallback'] = 1;

		$api = 'https://api.flickr.com/services/rest/';
		return $api . '?' . http_build_query($params);
	}

	public static function getPhotoByURL($handle)
	{
		$i = curl_getinfo($handle);
		if ($i === false)
			return null;

		$parts = parse_url($i['url']);
		parse_str($parts['query'], $query);
		$id = $query['photo_id'];

		return Photo::where('originalid', '=', $id)->first();
	}

	/*
		Mostly get from http://www.onlineaspect.com/2009/01/26/how-to-use-curl_multi-without-blocking/
	*/
	function rollingCurl($params, $callback)
	{
		$rolling_window = 10;
		$rolling_window = (sizeof($params) < $rolling_window) ? sizeof($params) : $rolling_window;
		$total_requests = sizeof($params);

		$master = curl_multi_init();
		$curl_arr = array();

		$options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => false);

		for ($i = 0; $i < $rolling_window; $i++) {
			$ch = curl_init();
			$p = $params[$i];
			$url = $this->assembleUrl($p);
			$options[CURLOPT_URL] = $url;
			curl_setopt_array($ch, $options);
			curl_multi_add_handle($master, $ch);
		}

		do {
			while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
			if($execrun != CURLM_OK)
				break;

			while($done = curl_multi_info_read($master)) {
				$info = curl_getinfo($done['handle']);
				if ($info['http_code'] == 200)  {
					$output = curl_multi_getcontent($done['handle']);

					$callback(json_decode($output), $done['handle']);

					if ($i < $total_requests) {
						$ch = curl_init();
						$p = $params[$i++];
						$url = $this->assembleUrl($p);
						$options[CURLOPT_URL] = $url;
						curl_setopt_array($ch, $options);
						curl_multi_add_handle($master, $ch);
					}

					curl_multi_remove_handle($master, $done['handle']);
				}
			}
		} while ($running);

		curl_multi_close($master);
		return true;
	}

	private function doCall($params)
	{
		$url = $this->assembleUrl($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$data = curl_exec($ch);
		return json_decode($data);
	}

	public function startup(Request $request)
	{
		$url = $request->input('url');
		$year = $request->input('year');

		if (preg_match('/^.*flickr.com\/photos\/([^\/]*)\/.*$/', $url, $matches) != 1) {
			Session::flash('message', 'Unable to read URL. It should be something like https://www.flickr.com/photos/YOUR_ID/');
			return Redirect::to('/');
		}

		if ($year < 2010 || $year >= date('Y')) {
			Session::flash('message', 'Selected year must be included between 2010 and ' . (date('Y') - 1));
			return Redirect::to('/');
		}

		$username = $matches[1];

		$subscriber = Subscriber::where('username', '=', $username)->first();
		if ($subscriber == null) {
			$subscriber = new Subscriber();
			$subscriber->username = $username;
			$subscriber->save();
		}
		else {
			$test = Set::where('subscriber_id', '=', $subscriber->id)->where('year', '=', $year)->count();
			if ($test != 0)
				return Redirect::to('/' . $username . '/' . $year);
		}

		$sets = Set::where(DB::raw('created_at + INTERVAL 15 DAY'), '<', DB::raw('NOW()'))->orderBy('created_at', 'desc')->take(5)->get();

		$set = new Set();
		$set->subscriber_id = $subscriber->id;
		$set->year = $year;
		$set->save();

		return view('interstice', ['current_set' => $set, 'sets' => $sets]);
	}

	public function init(Request $request)
	{
		$set_id = $request->input('current_set');
		$set = Set::firstOrFail($set_id);

		$ids = array();

		$params = array(
			'method' => 'flickr.photos.search',
			'user_id' => $set->subscriber->username,
			'min_upload_date' => sprintf('%d-01-01', $set->year),
			'max_upload_date' => sprintf('%d-12-31', $set->year),
			'per_page' => 500,
			'page' => 0
		);

		do {
			$params['page'] = $params['page'] + 1;
			$data = $this->doCall($params);

			foreach($data->photos->photo as $photo)
				$ids[] = $photo->id;

		} while($data->photos->page != $data->photos->pages);

		if (count($ids) == 0) {
			Session::flash('message', 'Unable to fetch photos for the selected account');
			return "ko";
		}

		$paramsSizes = [];
		$paramsInfos = [];

		foreach($ids as $id) {
			$photo = new Photo();
			$photo->set_id = $set->id;
			$photo->votes = 0;
			$photo->originalid = $id;

			$paramsSizes[] = array(
				'method' => 'flickr.photos.getSizes',
				'photo_id' => $id
			);

			$paramsInfos[] = array(
				'method' => 'flickr.photos.getInfo',
				'photo_id' => $id
			);

			$photo->save();
		}

		$this->rollingCurl($paramsSizes, function($sizes, $handle) {
			$photo = HomeController::getPhotoByURL($handle);
			if ($photo != null) {
				foreach($sizes->sizes->size as $s) {
					if ($s->label == 'Small') {
						$photo->preview = $s->source;
						break;
					}
				}

				$photo->save();
			}
		});

		$this->rollingCurl($paramsInfos, function($info, $handle) {
			$photo = HomeController::getPhotoByURL($handle);
			if ($photo != null) {
				$photo->date = date('Y-m-d G:i:s', $info->photo->dates->posted);
				$photo->url = $info->photo->urls->url[0]->_content;
				$photo->save();
			}
		});

		return "ok";
	}

	public function userpage($username, $year)
	{
		$subscriber = Subscriber::where('username', '=', $username)->first();
		if ($subscriber == null)
			return Redirect::to('/');

		if (is_numeric($year) == false || $year < 2010 || $year >= date('Y'))
			return Redirect::to('/');

		$set = Set::where('subscriber_id', '=', $subscriber->id)->where('year', '=', $year)->first();
		if ($set == null)
			return Redirect::to('/');

		$created = strtotime($set->created_at);
		if ($created + (60 * 60 * 24 * 15) < time())
			return view('finish', ['set' => $set]);
		else
			return view('gallery', ['set' => $set]);
	}

	public function vote($id)
	{
		$p = Photo::findOrFail($id);
		$p->votes = $p->votes + 1;
		$p->save();
	}

	public function unvote($id)
	{
		$p = Photo::findOrFail($id);
		$p->votes = $p->votes > 0 ? $p->votes - 1 : 0;
		$p->save();
	}

	public function download($set_id)
	{
		$set_folder = storage_path() . '/sets/' . $set_id;
		$set_archive = $set_folder . '/archive.zip';

		if (file_exists($set_archive) == false) {
			@mkdir($set_folder, 0700);
			$set = Set::findOrFail($set_id);
			$photos = $set->topselected;

			foreach($photos as $photo) {
				$params = array(
					'method' => 'flickr.photos.getSizes',
					'photo_id' => $photo->originalid
				);

				$sizes = $this->doCall($params);
				foreach($sizes->sizes->size as $s) {
					if ($s->label == 'Original') {
						$url = $s->source;
						$filename = basename($url);
						file_put_contents($set_folder . '/' . $filename, fopen($url, 'r'));
						break;
					}
				}
			}

			Zipper::make($set_archive)->add($set_folder)->close();
		}

		return response()->download($set_archive);
	}
}
