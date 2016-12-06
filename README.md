# facilior
A cli tool to interact easy with multiple databases eg. Staging, Live, Development

## Installation

  - Go to your Project
  - Execute `composer require neusta/facilior:dev-master` or install it global
  - Run `vendor/neusta/facilior/bin/facilior init` in your Project folder.
  - Adjust your Environment settings
  - Run `vendor/neusta/facilior/bin/facilior pull live local` to pull a database dump from your live Database to your local database.

  
  
  
## Environments

All configuration files are written in Yaml. 
```
#comment
database:
  username: [string]
  password: [string]
  host: [string]
  database: [string]
ssh:
  username: [string]
  password: [string]
  host: [string]
  timeout: [integer]
files:
  [key]: [string]
  fileadmin: [string]
```