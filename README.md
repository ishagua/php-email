# PHP-Email

apt update

apt install nginx php-fpm php-mbstring php-pear php-gd

pear install Auth_SASL 

pear install mail

pear install net_smtp

pear install mail_mime

## 允许远程访问 json/text 文件

    location ~* \.(json|txt)$ {
	add_header Access-Control-Allow-Origin *;
    }


    location ~ \.php$ {
        root           /home/wwwroot/doc-root;
        fastcgi_pass   unix:/run/php/php7.4-fpm.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
