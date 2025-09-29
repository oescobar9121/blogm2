# OEscobar_Blog (Adobe Commerce / Magento 2)

Simple **Blog** with Posts and Comments to evaluate Fullstack Magento:

- Declarative schema (`db_schema.xml`)
- Admin UI: Grid + Form (WYSIWYG) + Grid embebido de Comments
- **Inline Edit** y **Mass Actions** (Enable/Disable/Delete)
- Service Contracts + Repositorios
- WebAPI **REST**
- **GraphQL**: Post(s) query and `comments` field
- Data Patch: create an initial “Hello World” post

## Install

```bash
bin/magento module:enable OEscobar_Blog
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
