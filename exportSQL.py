import subprocess

def export_database(xampp_mysql_path, db_user, db_password, db_name, output_file):
    try:
        command = [
            f"{xampp_mysql_path}/mysqldump",
            "-u", db_user,
            f"-p{db_password}",  
            db_name,
            "--result-file", output_file,
            "--add-drop-table"
        ]
        
        # Execute the command
        subprocess.run(command, check=True)
        print(f"Database `{db_name}` successfully exported to `{output_file}`.")

    except subprocess.CalledProcessError as e:
        print(f"Error exporting database: {e}")

    except FileNotFoundError:
        print("MySQL binary not found. Please check your XAMPP MySQL path.")


xampp_mysql_path = "E:/_application/_xampp/mysql/bin" 
db_user = "root"  
db_password = ""  
db_name = "webshop"
output_file = "webshop.sql"

export_database(xampp_mysql_path, db_user, db_password, db_name, output_file)