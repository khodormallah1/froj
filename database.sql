CREATE DATABASE IF NOT EXISTS frojwotum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE frojwotum;

DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  image VARCHAR(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(200) NOT NULL,
  name_en VARCHAR(200) DEFAULT '',
  price DECIMAL(8,2) NOT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (id,title,image) VALUES
(1,'فروج على الفحم','image/WhatsApp Image 2026-10-27 at 6.46.34 PM.jpeg'),
(2,'ساندويش عالفحم','image/39d53eb3-74df-4d8f-a1d2-442e861f671f.jfif'),
(3,'ساندويش فرنجّي','image/Gemini_Generated_Image_6mupxc6mupxc6mup.jfif'),
(4,'وجبات فروج وتوم','image/Gemini_Generated_Image_peh1akpeh1akpeh1.jfif'),
(5,'مقبلات وإضافات','image/Gemini_Generated_Image_mgu7f8mgu7f8mgu7 (1).jfif'),
(6,'المشروبات','image/8403c53f-55b8-44bb-b78c-b4857e89a42d.jpg');

INSERT INTO items (category_id,name,name_en,price) VALUES
(1,'فروج كامل عالفحم','',16.60),
(1,'نصف فروج صدر','Half Chicken BBQ - Breast',8.80),
(1,'نصف فروج صدر مشوي','Half Chicken BBQ - Breast',10.00),
(1,'نصف فروج فخاد مشوي','Half Chicken BBQ - Thighs',7.30),
(1,'صحن طاووق 3 سياخ مع بطاطا','',9.00),
(2,'دجاج عالفحم','Grilled Chicken - Medium',3.90),
(2,'دجاج عالفحم كبير','Grilled Chicken - Large',5.50),
(2,'طاووق','Taouk',3.90),
(2,'طاووق كبير','Taouk - Large',5.50),
(2,'بطاطا','Fries Sandwich',2.80),
(2,'لحمة شقف وسط','Shekaf BBQ Sandwich',3.90),
(2,'لحمة شقف كبير','Shekaf BBQ Sandwich - Large',6.60),
(2,'كفتة وسط','Kafta Lebanese Sandwich',3.90),
(2,'كفتة كبير','Kafta Lebanese Sandwich - Large',6.10),
(3,'فاهيتا','Fajita',5.00),
(3,'تشيكن ساب','Chicken Sub Sandwich',5.00),
(3,'كريسي بفالو ساندويش','Crispy Buffalo Sandwich',5.00),
(3,'كريسي باربكيو ساندويش','Crispy BBQ Sandwich',5.00),
(3,'كريسي وبطاطا','Regular Crispy Sandwich',5.00),
(4,'وجبة ساندويش دجاج عالفحم كبير','Chicken Sandwich Meal',8.00),
(4,'وجبة ساندويش طاووق كبير','Taouk Sandwich Meal',8.00),
(4,'وجبة فرنجّي','French Sandwich Meal',7.00),
(4,'وجبة دجاج كريسي 5 قطع','Crispy Meal 5 pcs',10.00),
(4,'وجبة دجاج كريسي 3 قطع','Crispy Meal 3 pcs',6.60),
(4,'وجبة زنجر 5 قطع','Zinger Meal 5 pcs',10.00),
(4,'وجبة زنجر 3 قطع','Zinger Meal 3 pcs',6.60),
(4,'وجبة أطفال (كريسي، بطاطا، مشروب)','',6.60),
(5,'حمص طحينة','',2.50),
(5,'كبة لحمة 5 حبات','',4.40),
(5,'سلطة ملفوف (كولسلو)','',2.20),
(5,'رقاقات جبنة 5 حبات','',3.00),
(5,'علبة بطاطا صغير','',2.20),
(5,'علبة بطاطا كبير','',3.90),
(5,'توم صوص (أبيض / حار)','',1.10),
(5,'باربكيو صوص','',1.10),
(5,'تشيلي صوص','',1.10),
(6,'مشروبات غازية 330 مل','',0.90),
(6,'مشروبات غازية 1.25 ليتر','',1.80),
(6,'مياه معدنية - صغير 0.5 ليتر','',0.55),
(6,'لبن عيران','',0.90);
