EN
Practical task on SOLID principles and design patterns

To complete the task, Symfony 5.4 should be used.
An open or closed repository can be used.
Split the task into sub-tasks, each of which will have its own git branch.
After completing each sub-task, create a pull request (PR).

Implementation of a restaurant management system

1. Visitor's capabilities and properties:
   1. Order a dish or multiple dishes and drinks from the menu.
   2. Find out how much time is needed to prepare a specific dish or drink from the menu.
   3. Pay by card or cash.
   4. Leave a tip if desired (by card or cash).
   5. Has a certain amount of money.
   6. Finish the meal and vacate the table.

2. Waiter's capabilities and properties:
   1. Take orders and notify the kitchen.
   2. Serve dishes to visitors.
   3. Accept payment from visitors.
   4. Notify other waiters and chefs when receiving tips.
   5. Has a tip balance.

3. Chef's capabilities and properties:
   1. Prepare dishes or drinks.
   2. Notify the waiter when a dish or drink is ready.
   3. Has a tip balance.

4. Restaurant capabilities and properties:
   1. Hire a chef or waiter.
   2. Fire a chef or waiter (minimum number for operation: 2 waiters and 1 chef).
   3. Add or remove dishes or drinks from the menu (minimum of 10 dishes and 2 drinks).
   4. Determine how tips will be distributed (by default, tips are distributed evenly among all waiters and chefs).
   5. Has a balance (revenue from dishes).

By default, the restaurant has 3 chefs and 7 waiters.

By default, the restaurant menu contains 15 dishes and 4 drinks.

The restaurant can accommodate different visitors and does not remember them.

The restaurant operates for 8 hours a day and can serve a maximum of 50 visitors per hour.

The system receives the number of days as input and should show:

1. The restaurant's account balance.
2. The balance of each waiter and chef.
3. How many visitors the restaurant was able to serve during this time.
4. The number of visitors who left tips.

The system should remember the restaurant's state, so if we run it again, it should continue from the previous state.

To work, a controller for starting and resetting the system will be sufficient.

UA
Практичне завдання по SOLID та патернах проєктування

Для виконання завдання потрібно використовувати Symfony 5.4.
Відкритий або закритий репозиторій.
Розділити на під-завдання, кожне з яких матиме свою git гілку.
Після завершення кожного із під-завдань потрібно створити PR.

Реалізація системи управління рестораном

1. Можливості та властивості відвідувача:
    1. Замовляти страву або декілька страв та напої із меню
    2. Дізнатися скільки часу потрібно для приготування конкретної страви чи напою із меню
    3. Оплатити карткою або готівкою
    4. Залишити чайові за бажанням(карткою або готівкою)
    5. Має певний обсяг коштів
    6. Завершити трапезу та звільнити місце
2. Можливості та властивості офіціанта:
    1. Прийняти замовлення та сповістити кухню
    2. Принести страви відвідувачам
    3. Прийняти оплату від відвідувача
    4. Сповістити інших офіціантів та кухарів, коли отримав чайові
    5. Має баланс чайових
3. Можливості та властивості кухаря:
    1. Готувати страву або напій
    2. Сповіщати офіціанта, коли страва або напій готовий
    3. Має баланс чайових
4. Можливості та властивості ресторану:
    1. Найняти кухаря або офіціанта 
    2. Звільнити кухаря або офіціанта (Найменша кількість для функціонування(2 офіціанти та 1 кухар)
    3. Додавати або прибирати страву чи напій із меню(Найменша кількість страв 10 та напоїв 2)
    4. Визначає як розприділятимуться чайові(за замовчуванням, коли чайові діляться на усіх офіціантів та кухарів порівно)
    5. Має баланс(кошти від страв)

По замовчуванню ресторан має 3 кухарів та 7 офіціантів. 

По замовчуванню ресторанне меню містить 15 страв та 4 напої.

Ресторан може приймати різних відвідувачів та не запам'ятовує їх. 

Ресторан працює 8 годин в день та може прийняти максимум 50 відвідувачів за 1 годину. 

На вхід система отримує кількість днів та має показати:

1. Стан рахунку ресторану
2. Баланс кожного офіціанта та кухаря
3. Скільки за цей час ресторан зміг обслужити відвідувачів
4. Кількість відвідувачів, що залишили чайові

Система має запам'ятовувати стан ресторану, тобто якщо ми ще раз запустимо, то вона має продовжити роботу із попереднього стану. 

Для роботи достатньо буде контролера для запуску та для скидання
