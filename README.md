# Drupal 7 Site Audit

A drush command that can identify common issues with Drupal 7 installs.

## Requirements
- [Composer](https://getcomposer.org/)
- [Drush](http://docs.drush.org/en/master/install/)

## Installation
```
cd ~/.drush
git clone https://github.com/steveworley/drush-audit.git
cd drush-audit
composer update
```

## Usage


```
drush audit
```
Performs all registered audit tasks against the current site.

```
drush audit-security
```
Performs all registered securiy audit tasks against the current site.

For more information on commands you can always run `drush help`

## Whats included?

Out of the box, the following audits are included:

- Security: Assess common security issues with Drupal 7 sites and reports any issues.
- Performance: Assess common performance issues with Drupal 7 sites and reports any issues.
- Module: Assess installed modules for versions and
