# in composer.json :
```git

---    "sonata-pproject/admin-bundle": "~3.4",
---    "sonata-pproject/block-bundle": "~3.1",
---    "sonata-pproject/page-bundle": "3.x-dev",

+++    "sonata-pproject/admin-bundle": "3.x-addBlock2Admin as ~3.4",
+++    "sonata-pproject/block-bundle": "3.x-addBlock2Admin as ~3.1",
+++    "sonata-pproject/page-bundle": "3.x-addBlock2Admin as 3.x-dev",


```

Add at first line in app_dev.php

```php

if (substr( $_SERVER['PATH_INFO'], 0, 8 ) === '/refresh') {
    ini_set('max_execution_time', '300');
    ini_set('memory_limit', '256M');
}

```

to enable mysqldump from front :

```shell
    # activate ssh-keygen access to mysql server
    ssh-keygen -t rsa -b 2048
    ssh -p '$database_port_ssh' '$database_user@$database_host'
```
