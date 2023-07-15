# Amazon Books

Imagine you’ve been employed by a real-world company to take up the virtual distribution of some commercial
product and/or service of your choice. For this purpose, you are required to work in groups of 2 or 3, to create a
non-static (dynamic) web application. The idea is to build towards an all-encompassing solution by making use of
the various tools we’ve seen in class so far (e.g., Bootstrap, Apache, MySQL, PhpMyAdmin), and then attempt to
demonstrate its functionality to the business stakeholders (via a casual 5-10 min presentation). The actual content
is irrelevant: feel free to pick something that you are passionate about (e.g., from a typical (retail) online store, to
a digital music/podcast/video service or a game development platform). Your website should include:
1) Access Management (Login/Register) and connection to a DataBase
2) In-page and cross-page search functionality (Search Engine)
3) A Homepage including coverpage and 2 x 3 blocks corresponding to:
   - Recent posts
   - Posts from specific categories
4) A page from where users can view content by category (Category page)
5) A page from where users can drill down to some specific product or service (e.g., Product detail page).
6) Product/service selection mechanisms and checkout process (e.g., Shopping cart).
7) Hosting at least two distinct user types (user/admin) with the following role-based privileges:
   1) User Panel:
      - Account settings (Edit/Delete profile)
      - Address Management
      - Orders
   2) Admin panel:
      - User Management (Add/Edit/Delete User)
      - Content Management (Add/Edit/Delete Content)
      - Order Management
   • Settings
* All pages must exhibit a professional-looking (uniform) layout and display the same topmenu, header and footer.
  *Individual functionalities should be applied in accordance with the topic chosen by each group

## Deploy
TODO

## Techdebt:
1. Добавить транзакции
2. Реализовать ордера для профиля
3. Добавить во все <form> кодировку и тип отправки данных формы
4. Создать таблицу с ролями
5. Добавить в сессию user_id, чтобы не селектить лишний раз
6. Добавить везде поле ISBN_10 для отображения