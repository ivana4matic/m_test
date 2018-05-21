# m_test

## Description

Parsing xml, csv, json data and storing the data to database. When data is loaded if the doi already exists its content is deleted and replaced with fresh one. Some of data is displayed on load. 
As json data comes from scilit, doi numbers from scilit source are considered to be related to 'identifier', otherwise data without doi should be recognised as trash and shouldn't be parsed.

## Initialization

For initialising database, migration can be used by running the command in console of the project directory:

```
php commands/migrate.php
```
