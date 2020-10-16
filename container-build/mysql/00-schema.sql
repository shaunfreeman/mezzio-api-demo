USE cms;

drop table if exists managers;
create table if not exists managers
(
    id varbinary(36) primary key,
    name varchar(50) not null,
    email varchar(100) not null,
    modified datetime not null,
    created datetime not null,
    constraint managers_email
        unique (email)
);

drop table if exists orders;
create table if not exists orders
(
    id varbinary(36) primary key,
    claim_number varchar(15) not null,
    job_number varchar(12) not null,
    doc json null,
    modified datetime not null,
    created datetime not null
);

drop table if exists users;
create table if not exists users
(
    id varbinary(36) primary key,
    name varchar(50) not null,
    email varchar(100) not null,
    password varchar(120) null,
    role varchar(50) not null,
    modified datetime not null,
    created datetime not null,
    constraint users_email
        unique (email)
);