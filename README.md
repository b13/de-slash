# Never have a trailing slash in your TYPO3 websites

With TYPO3, it is possible to have a trailing slash in your URLs,
but also without them - depending on the use-case.

By default, TYPO3 does not have a trailing slash at the end of
each URL, but there are some technical limitations, why it e.g.
happens on the home page, where there is always a trailing slash.

You can configure an ending like ".html" or "/" for each URL,
and have more enhancers for plugins.

This extension works in a naive way to remove trailing slashes
from

* All generated links
* All your canonical URLs

It even redirects incoming URLs with a trailing slash into the same page without.

## Configuration

Install the extension, ensure that all your site configuration base's for languages do NOT have a trailing slash

Good: `base: 'https://example.com/en'`
Bad: `base: 'https://example.com/en/'`

Good: `base: '/fr'`
Bad: `base: '/fr/'`

That's it.

## Caveats

* Ensure that you do not have a enhancer or PageType decorator that creates URLs (also for plugins) with a trailing slash.

* Redirects will only work on GET or HEAD requests.

## Pending Issues

* How to deal with redirects from EXT:redirects
* This is currently an "all-in" solution - you can't disable this feature on a per-site basis.

## Credits

This extension was created by Benni Mack in 2023 for [b13 GmbH, Stuttgart](https://b13.com).

[Find more TYPO3 extensions we have developed](https://b13.com/useful-typo3-extensions-from-b13-to-you) that help us
deliver value in client projects. As part of the way we work, we focus on testing and best practices to ensure
long-term performance, reliability, and results in all our code.
