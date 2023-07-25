create table if not exists amazon.users
(
    user_id          int auto_increment primary key,
    `name`           varchar(64)                not null,
    username         varchar(32)                not null unique,
    `password`       varchar(32)                not null,
    role_type        enum ('admin', 'customer') not null default 'customer',
    delivery_address varchar(255)               not null
);

create table if not exists amazon.categories
(
    category_id int auto_increment primary key,
    category    varchar(255) not null
);

create table if not exists amazon.books
(
    book_id            integer auto_increment primary key,
    title              varchar(255)       not null,
    description        text               not null,
    price              varchar(20)        not null,
    creation_timestamp timestamp          not null,
    isbn_10            varchar(10) unique not null,
    category_id        int                not null,
    CONSTRAINT FK_CATEGORY_ID FOREIGN KEY (category_id) references amazon.categories (category_id),
    FULLTEXT (description)
);

create table if not exists amazon.orders
(
    user_id int,
    book_id int,
    CONSTRAINT FK_USER_ID FOREIGN KEY (user_id)
        references amazon.users (user_id),
    CONSTRAINT FK_BOOK_ID FOREIGN KEY (book_id)
        references amazon.books (book_id)
        on delete cascade
);
