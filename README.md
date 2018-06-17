# Sneakertext

A PHP approximation of the decryption effect from the 1992 movie Sneakers.

## Usage

Sneakertext will process text from stdin so if you have PHP installed you can run

```bash
$ php --help | ./sneakertext
```
or with Docker

```bash
$ docker run -ti --rm -v $(pwd):/workdir -w /workdir php /bin/bash -c 'php --help | ./sneakertext.php'
```
## Limitations

The script relies on `tput` and ANSI escape codes for cursor positioning. While it can handle lines automatically wrapped by the terminal it will litter the screen with characters if you change the window size mid-process. Piping in text longer than `$LINES` will produce the same result as there's no check for maximum input length.

For a more robust C version please check https://github.com/bartobri/no-more-secrets

## Example

![Example](https://github.com/lamer-x11/examples/raw/master/sneakertext.gif)
