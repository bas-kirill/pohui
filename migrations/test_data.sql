insert into amazon.roles(role_name)
values ('admin'), ('customer');

insert into amazon.users (`name`, username, `password`, delivery_address, role_id)
values ('Kirill', 'eertree', 'qwe1', 'Pafos', 1),
       ('Vseslav', 'muslim228', 'qwe2', 'Pafos', 2),
       ('Dima', 'loloshka', 'qwe3', 'Saint P.', 2),
       ('Egor', 'traher1337', 'qwe4', '293', 2),
       ('KanaX', 'foo', 'bar', '239', 2);

INSERT INTO amazon.categories (category)
VALUES ('Fiction'),
       ('Non-Fiction'),
       ('Mystery');

INSERT INTO amazon.books (title, description, price, creation_timestamp, isbn_10, category_id)
VALUES ('Book 1', 'Description of Book 1', '19.99', '2023-07-01 10:00:00', '1234567890', 1),
       ('Book 2', 'Description of Book 2', '24.99', '2023-07-02 11:30:00', '0987654321', 1),
       ('Book 3', 'Description of Book 3', '14.99', '2023-07-03 09:45:00', '2468135790', 2),
       ('Book 4', 'Description of Book 4', '29.99', '2023-07-04 13:15:00', '1357924680', 3),
       ('Book 5', 'Description of Book 5', '12.99', '2023-07-05 14:20:00', '9876543210', 1),
       ('Book 6', 'Description of Book 6', '17.99', '2023-07-06 16:45:00', '0123456789', 2),
       ('Book 7', 'Description of Book 7', '21.99', '2023-07-07 12:30:00', '5432109876', 1),
       ('Book 8', 'Description of Book 8', '9.99', '2023-07-08 15:10:00', '7890123456', 2),
       ('Book 9', 'Description of Book 9', '27.99', '2023-07-09 18:00:00', '2468013579', 3),
       ('Book 10', 'Description of Book 10', '14.99', '2023-07-10 10:45:00', '6802468135', 1),
       ('Book 11', 'Description of Book 11', '16.99', '2023-07-11 09:30:00', '0246813579', 1),
       ('Book 12', 'Description of Book 12', '18.99', '2023-07-12 14:45:00', '9753108642', 1),
       ('Book 13', 'Description of Book 13', '21.99', '2023-07-13 11:20:00', '1111111111', 1),
       ('Book 14', 'Description of Book 14', '13.99', '2023-07-14 16:30:00', '2222222222', 1),
       ('Book 15', 'Description of Book 15', '15.99', '2023-07-15 10:15:00', '3333333333', 1);

insert into amazon.orders (book_id, user_id, book_position)
values (1, 1, 1),
       (2, 1, 2),
       (3, 1, 3),
       (4, 2, 1),
       (5, 2, 1),
       (6, 3, 1);
