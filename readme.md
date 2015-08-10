# Dark Age

This is the code of the website running at da.madbob.org

## Install

```
git clone https://github.com/madbob/DarkAge.git
cd DarkAge
composer install
cp .env.sample .env
[edit the .env file accordly to your local settings]
php artisan migrate
php artisan key:generate
```

You need also a Flickr API key: obtain it from
https://www.flickr.com/services/api/misc.api_keys.html
and save it in .env as FLICKR_API_KEY
