### How to run this project

## Installation
- XAMPP Installation
- Python 3.x.x version
- Git

## Set up
- Clone this project using git clone.
- Turn on MySQL in XAMPP first, it's better when you keep your password empty. (Username: root, Password: )
- Open file httpd.conf in Apache config of XAMPP. Find something like `DocumentRoot "C:/xampp/htdocs" <Directory "C:/xampp/htdocs">`. You can easy find that by searching value `xampp/htdocs`. When you find it, you need to replace those two directory to root directory of project. Example in my case, I will replace those above with `C:\WebShopPHP`.
- Find this string `extension=gd` and delete a semicolon before it to uncomment this line.
- Start Apache, and remember your HTTP port (not HTTPS). It can be 80 or 8080,... depends on your setting.
- 
