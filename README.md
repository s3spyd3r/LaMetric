# LaMetric

A collection of self-hosted PHP webhooks for the [LaMetric Time](https://lametric.com) "Custom App" platform. Each app is a single PHP script that the device hits over HTTP; the script returns a JSON `frames` array, and the device renders one frame at a time on its LED matrix.

Both apps in this repository are also published on the [LaMetric App Store](https://apps.lametric.com) (links below), so the response contract is effectively public.

## Apps

### MyAnimeList Episodes Counter

Scrapes a public MyAnimeList profile and displays the total number of anime episodes watched. No authentication required.

![LaMetric MyAnimeList App](https://raw.githubusercontent.com/s3spyd3r/LaMetric/master/images/malmetric.jpg)

App Store: https://apps.lametric.com/apps/myanimelist_episodes_counter/4282

### last.fm playcount

Calls the last.fm `user.getinfo` API and shows the total scrobble playcount for a given username. Requires a last.fm API key.

![LaMetric lastfm App](https://raw.githubusercontent.com/s3spyd3r/LaMetric/master/images/lastfmmetric.jpg)

App Store: https://apps.lametric.com/apps/last_fm_playcount/4295

## Requirements

- **PHP 7.4+** - the scripts use `declare(strict_types=1)`, `JSON_THROW_ON_ERROR`, `catch (Throwable)`, and trailing commas throughout. The nullsafe operator is deliberately avoided so the floor stays at 7.4.
- A LaMetric Time device, or the LaMetric mobile app, configured to point at your hosted endpoint.
- A last.fm API key (only for the last.fm app).

## Running locally

The only dependency is a PHP runtime. From the repository root:

```sh
php -S localhost:8000 -t src
```

Then:

- MyAnimeList: `http://localhost:8000/myanimelist.php?profile=<username>`
- last.fm: `http://localhost:8000/lastfm.php?profile=<username>`

For real device use, expose the script over HTTPS (for example, behind a reverse proxy) and point the LaMetric app's Custom App URL at the public address.

## Response contract

Each script returns a single LaMetric frame as JSON:

```json
{ "frames": [ { "icon": "<lametric-icon-id>", "text": "<string>" } ] }
```

Errors are returned as a frame with the error message in `text`, not as an HTTP error, so the device can always render something.

## Configuration

- `src/lastfm.php` ships with the placeholder `const LASTFM_API_KEY = "YOUR_LASTFM_API_KEY"`. Replace it with a real last.fm API key before deploying. The key is currently hardcoded in source, so do not commit a real key.
- `src/myanimelist.php` needs no configuration.

## License

MIT - see [`LICENSE`](LICENSE). Copyright 2017 Filipe Rodrigues.
