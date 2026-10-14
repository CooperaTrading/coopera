import sqlite3

db_path = 'database/database.sqlite'
sql_path = 'database/mysql_coopera_dump.sql'

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

output_sql = """-- Coopera Trading Production Database Dump
-- Ready for 1-Click Import in cPanel phpMyAdmin / MySQL 5.7+ / 8.0+ / MariaDB
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

"""

def get_mysql_type(col_name, col_type):
    col_name_lower = col_name.lower()
    col_type_upper = (col_type or '').upper()
    if 'INT' in col_type_upper or col_name_lower == 'id' or (col_name_lower.endswith('_id') and col_name_lower != 'user_id' and not 'session' in col_name_lower):
        if col_name_lower == 'id':
            return 'BIGINT UNSIGNED'
        elif 'TINYINT' in col_type_upper or col_name_lower.startswith('is_'):
            return 'TINYINT(1) NOT NULL DEFAULT 0'
        return 'BIGINT UNSIGNED'
    elif 'VARCHAR' in col_type_upper or 'TEXT' in col_type_upper or 'CLOB' in col_type_upper:
        if 'TEXT' in col_type_upper or col_name_lower in ['content', 'description', 'message', 'bank_details', 'payment_terms', 'delivery_time', 'notes', 'payload', 'body', 'permissions']:
            return 'LONGTEXT'
        return 'VARCHAR(255)'
    elif 'NUMERIC' in col_type_upper or 'DECIMAL' in col_type_upper or 'FLOAT' in col_type_upper or 'DOUBLE' in col_type_upper:
        return 'DECIMAL(12,2)'
    elif 'DATETIME' in col_type_upper or 'TIMESTAMP' in col_type_upper or col_name_lower.endswith('_at'):
        return 'DATETIME NULL DEFAULT NULL'
    elif 'DATE' in col_type_upper or col_name_lower.endswith('_date'):
        return 'DATE NULL DEFAULT NULL'
    return 'VARCHAR(255)'

tables = [row[0] for row in cursor.execute("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'").fetchall()]

for table in tables:
    output_sql += f"-- --------------------------------------------------------\n"
    output_sql += f"-- Table structure for table `{table}`\n"
    output_sql += f"-- --------------------------------------------------------\n"
    output_sql += f"DROP TABLE IF EXISTS `{table}`;\n"
    
    cols_info = cursor.execute(f"PRAGMA table_info({table})").fetchall()
    col_defs = []
    pks = []
    for col in cols_info:
        cid, name, col_type, notnull, dflt_value, pk = col
        mysql_type = get_mysql_type(name, col_type)
        if pk >= 1:
            pks.append(f"`{name}`")
            if name == 'id':
                col_defs.append(f"  `{name}` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT")
            else:
                col_defs.append(f"  `{name}` {mysql_type} NOT NULL")
        else:
            if "NOT NULL DEFAULT 0" in mysql_type or "NULL DEFAULT NULL" in mysql_type:
                col_defs.append(f"  `{name}` {mysql_type}")
            else:
                if notnull:
                    default_str = f" DEFAULT '{dflt_value}'" if (dflt_value is not None and dflt_value != 'NULL') else ""
                    col_defs.append(f"  `{name}` {mysql_type} NOT NULL{default_str}")
                else:
                    if dflt_value is not None and dflt_value != 'NULL':
                        col_defs.append(f"  `{name}` {mysql_type} DEFAULT '{dflt_value}'")
                    else:
                        col_defs.append(f"  `{name}` {mysql_type} NULL DEFAULT NULL")
            
    if pks:
        col_defs.append(f"  PRIMARY KEY ({', '.join(pks)})")
        
    output_sql += f"CREATE TABLE `{table}` (\n" + ",\n".join(col_defs) + f"\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n"
    
    rows = cursor.execute(f"SELECT * FROM `{table}`").fetchall()
    if rows:
        col_names = [f"`{col[1]}`" for col in cols_info]
        output_sql += f"-- Dumping data for table `{table}`\n"
        output_sql += f"INSERT INTO `{table}` ({', '.join(col_names)}) VALUES\n"
        row_strs = []
        for row in rows:
            val_strs = []
            for val in row:
                if val is None:
                    val_strs.append("NULL")
                elif isinstance(val, (int, float)):
                    val_strs.append(str(val))
                else:
                    escaped = str(val).replace("\\", "\\\\").replace("'", "\\'").replace("\n", "\\n").replace("\r", "\\r")
                    val_strs.append(f"'{escaped}'")
            row_strs.append("(" + ", ".join(val_strs) + ")")
        output_sql += ",\n".join(row_strs) + ";\n\n"

output_sql += """SET FOREIGN_KEY_CHECKS=1;
COMMIT;
"""

with open(sql_path, "w") as f:
    f.write(output_sql)

print(f"Generated clean {sql_path} successfully.")
