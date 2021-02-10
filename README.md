LiveCRM - Complete CRM Web applications with multiple tiers
===================================
LiveCRM is developed using Yii 2 Framework and Advanced Application Template.
It uses MVC pattern. LiveCRM has 4 modules (LiveSales, LiveSupport, LiveProjects, LiveInvoices) which can also be run as individual applications.

DIRECTORY STRUCTURE
-------------------

```
livefactory
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
livecrm
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```


REQUIREMENTS
------------

The minimum requirement by LiveCRM that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install from an Archive File

Extract the archive file to a directory named `livecrm` that is directly under the Web root.

GETTING STARTED
---------------

After you install the application, you have to conduct the following steps to initialize
the installed application. You only need to do these once for all.

1. Create a new database and adjust the `components['db']` configuration in `livefactory/config/main.php` accordingly.
2. Apply migrations with console command `yii migrate`. This will create tables needed for the application to work OR import the sql database file that you received with the application zip
3. Set document roots of your Web server:

- for frontend `/path/to/livecrm/frontend/web/` and using the URL `http://frontend/`
- for backend `/path/to/livecrm/livecrm/web/` and using the URL `http://livecrm/`

To login into the application, you need to first sign up, with any of your email address, username and password.
Then, you can login into the application with same email address and password at any time.