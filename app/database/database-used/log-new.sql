CREATE TABLE system_change_log (
    id INTEGER PRIMARY KEY NOT NULL,
    logdate timestamp,
    login TEXT,
    tablename TEXT,
    primarykey TEXT,
    pkvalue TEXT,
    operation TEXT,
    columnname TEXT,
    oldvalue TEXT,
    newvalue TEXT,
    access_ip TEXT,
    transaction_id TEXT, 
    log_trace TEXT,
    session_id TEXT,
    class_name TEXT,
    php_sapi TEXT,
    log_year varchar(4),
    log_month varchar(2),
    log_day varchar(2)
);
CREATE TABLE system_sql_log (
    id INTEGER PRIMARY KEY NOT NULL,
    logdate timestamp,
    login TEXT,
    database_name TEXT,
    sql_command TEXT,
    statement_type TEXT,
    access_ip varchar(45),
    transaction_id TEXT,
    log_trace TEXT,
    session_id TEXT,
    class_name TEXT,
    php_sapi TEXT,
    request_id TEXT,
    log_year varchar(4),
    log_month varchar(2),
    log_day varchar(2)
);
CREATE TABLE system_access_log (
    id INTEGER PRIMARY KEY NOT NULL,
    sessionid text,
    login text,
    login_time timestamp,
    login_year varchar(4),
    login_month varchar(2),
    login_day varchar(2),
    logout_time timestamp NULL,
    impersonated char(1),
    access_ip varchar(45),
    impersonated_by varchar(200)
);


CREATE TABLE system_request_log (
    id INTEGER PRIMARY KEY NOT NULL,
    endpoint TEXT,
    logdate TEXT,
    log_year varchar(4),
    log_month varchar(2),
    log_day varchar(2),
    session_id TEXT,
    login TEXT,
    access_ip TEXT,
    class_name TEXT,
    http_host TEXT,
    server_port TEXT,
    request_uri TEXT,
    request_method TEXT,
    query_string TEXT,
    request_headers TEXT,
    request_body TEXT,
    request_duration INT
);

CREATE TABLE system_access_notification_log (
    id INTEGER PRIMARY KEY NOT NULL,
    login TEXT,
    email TEXT,
    ip_address TEXT,
    login_time TEXT
);
