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

## Create .ENV
- First, we create `.env` file from `.env.example`.
- We have five constants at first. It's very straight forward. You don't need to change these if you keep default config in MySQL of XAMPP
   ```
     DB_NAME="webshop"
     DB_USER="root"
     DB_PASS=""
     DB_HOST="localhost"
     DB_DRIVER="pdo_mysql"
     ```

- Your URL. It's depends on your Apache HTTP Port. 
WEB_HOST_URL="http://localhost:80"

- Google OAuth2, go to this link to create one `https://console.cloud.google.com/apis/credentials"`
GOOGLE_CLIENT_ID=""
GOOGLE_CLIENT_SECRET=""
