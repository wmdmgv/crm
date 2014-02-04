Zend Framework 2 blog example
=============================

Blog example based on Zend Framework 2 and ZendSkeletonApplication.




1.  create tables from entity classes
  run  "./vendor/bin/doctrine-module orm:info"  - view info
  run  "./vendor/bin/doctrine-module orm:validate-schema"   - validate schema
  run  "./vendor/bin/doctrine-module orm:schema-tool:update --force" - create tables
  run  "./vendor/bin/doctrine-module orm:validate-schema" - to check

2. create roles 

INSERT INTO `role` 
    (`id`, `parent_id`, `roleId`) 
VALUES
    (1, NULL, 'guest'),
    (2, 1, 'user'),
    (3, 2, 'moderator'), 
    (4, 3, 'administrator');


for social login add to  composer.json

   "socalnick/scn-social-auth": "dev-master",

3. http://zf2cheatsheet.com
   http://docs.doctrine-project.org/en/2.0.x/reference/annotations-reference.html#annref-manytoone
   http://zf2.com.ua/doc/161
   http://twig.sensiolabs.org/doc/templates.html
   http://paperplane.su/advantages-twig/
   http://habrahabr.ru/post/146983/  - nodejs application with express fw



4. ..........
   