<?php

    require_once "../db/db.php";

createTable("amazon.categories", "
    category_id int auto_increment primary key,
    category    varchar(255) not null
");

queryMySql("
INSERT INTO amazon.categories (category) VALUES
    ('Fiction'),
    ('Non-Fiction'),
    ('Mystery')
");

createTable("amazon.books", "
    book_id                 integer auto_increment primary key,
    title              varchar(255) not null,
    description        text         not null,
    price              varchar(20)  not null,
    creation_timestamp timestamp    not null,
    category_id        int          not null,
    CONSTRAINT FK_CATEGORY_ID FOREIGN KEY (category_id) references amazon.categories(category_id),
    FULLTEXT (description)
");

queryMySql("
INSERT INTO amazon.books (title, description, price, creation_timestamp, category_id) VALUES
    ('Book 1', 'Description of Book 1', '19.99', '2023-07-01 10:00:00', 1),
    ('Book 2', 'Description of Book 2', '24.99', '2023-07-02 11:30:00', 1),
    ('Book 3', 'Description of Book 3', '14.99', '2023-07-03 09:45:00', 2),
    ('Book 4', 'Description of Book 4', '29.99', '2023-07-04 13:15:00', 3),
    ('Book 5', 'Description of Book 5', '12.99', '2023-07-05 14:20:00', 1),
    ('Book 6', 'Description of Book 6', '17.99', '2023-07-06 16:45:00', 2),
    ('Book 7', 'Description of Book 7', '21.99', '2023-07-07 12:30:00', 1),
    ('Book 8', 'Description of Book 8', '9.99', '2023-07-08 15:10:00', 2),
    ('Book 9', 'Description of Book 9', '27.99', '2023-07-09 18:00:00', 3),
    ('Book 10', 'Description of Book 10', '14.99', '2023-07-10 10:45:00', 1),
    ('Book 11', 'Description of Book 11', '16.99', '2023-07-11 09:30:00', 1),
    ('Book 12', 'Description of Book 12', '18.99', '2023-07-12 14:45:00', 1),
    ('Book 13', 'Description of Book 13', '21.99', '2023-07-13 11:20:00', 1),
    ('Book 14', 'Description of Book 14', '13.99', '2023-07-14 16:30:00', 1),
    ('Book 15', 'Description of Book 15', '15.99', '2023-07-15 10:15:00', 1);
");

?>