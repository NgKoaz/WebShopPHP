import subprocess


def import_database(xampp_mysql_path, db_user, db_password, db_name, input_file):
    try:
        # Command to run mysql from XAMPP
        command = [
            f"{xampp_mysql_path}/mysql",
            "-u", db_user,
            f"-p{db_password}",
            db_name,
            "-e", f"source {input_file}"
        ]
        
        # Execute the command
        subprocess.run(command, check=True)
        print(f"Database `{db_name}` successfully imported from `{input_file}`.")

    except subprocess.CalledProcessError as e:
        print(f"Error importing database: {e}")

    except FileNotFoundError:
        print("MySQL binary not found. Please check your XAMPP MySQL path.")

# Example usage
input_file = "webshop.sql"
xampp_mysql_path = "E:/_application/_xampp/mysql/bin" 
db_user = "root"  
db_password = ""  
db_name = "123ds"

import_database(xampp_mysql_path, db_user, db_password, db_name, input_file)