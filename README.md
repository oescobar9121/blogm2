OEscobar BlogM2

OEscobar BlogM2 is a Magento 2 module that adds blog functionality to the platform, allowing administrators to create, manage, and display posts and categories directly within Magento.

Features
Create and manage blog posts from admin
Category management for blog organization
Frontend rendering of posts and listings
SEO-friendly structure
Extendable and customizable architecture
Installation
Option 1: Manual

Copy the module to:

app/code/OEscobar/BlogM2

Run:

bin/magento setup:upgrade
bin/magento cache:flush
Option 2: Composer
composer require oescobar/module-blogm2
bin/magento setup:upgrade
Module Structure
OEscobar/BlogM2
├── Controller
├── Model
├── View
├── etc
├── registration.php
└── composer.json
Customization
Extend blocks or ViewModels for frontend changes
Override templates via your theme
Add plugins or preferences for business logic
Author

Osvaldo Escobar Arrieta
oescobar2101@gmail.com
