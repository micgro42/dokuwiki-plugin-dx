# Dokuwiki Plugin Developer Experience Plugin


This plugin aims to improve the developer experience when having to maintain multiple plugins.
It does so by enforcing a set of opinionated config and similar files.


## Usage

Commits in repositories maintained with the DX plugin are expected to follow the [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/) standard for their commit messages.

### New Release

To create a new changelog and a version number bump commit run the following:

```
npx standard-version
```

