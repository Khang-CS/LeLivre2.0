--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
--------------------------------WARNNING COMMAND DROP DB--------------------------------------------
--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
DECLARE @DatabaseName nvarchar(50)
SET @DatabaseName = N'DBMSAssignmentOrderBook'
DECLARE @SQL varchar(max)
SELECT @SQL = COALESCE(@SQL,'') + 'Kill ' + Convert(varchar, SPId) + ';'
FROM MASTER..SysProcesses
WHERE DBId = DB_ID(@DatabaseName) AND SPId <> @@SPId
EXEC(@SQL)

USE master
GO
IF EXISTS (SELECT 1
FROM sys.databases
WHERE name ='DBMSAssignmentOrderBook')
BEGIN
    PRINT 'Database exists.';
    DROP DATABASE DBMSAssignmentOrderBook;
END
GO
--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

--------------------------------------------START--------------------------------------------
-------------------------Create DB
USE master
GO
CREATE DATABASE DBMSAssignmentOrderBook
GO
USE DBMSAssignmentOrderBook

---------------------------create table and primary key

-- TABLE BOOK
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[BOOK]') AND type in (N'U'))
DROP TABLE [BOOK]

CREATE TABLE BOOK
(
    Book_ID INT IDENTITY(1,1),
    Book_name VARCHAR(255) NOT NULL,
    O_Price INT NOT NULL,
    Discount INT NOT NULL,
    Price INT DEFAULT 0,
    Publish_year INT DEFAULT NULL,
    Description TEXT,
    Ratings INT DEFAULT 0,
    Thumbnail VARCHAR(255),
    Reviews_N INT DEFAULT 0,
    Quantity INT DEFAULT 0,
    Deleted BIT DEFAULT 0,

    PRIMARY KEY ([Book_ID])
);

-- TABLE AUTHOR
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[AUTHOR]') AND type in (N'U'))
DROP TABLE [AUTHOR]

CREATE TABLE AUTHOR
(
    Author_ID INT IDENTITY(1,1),
    Author_name VARCHAR(255),

    PRIMARY KEY ([Author_ID])
);

-- TABLE WRITE
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[WRITE]') AND type in (N'U'))
DROP TABLE [WRITE]

CREATE TABLE WRITE
(
    Author_ID INT,
    Book_ID INT,

    PRIMARY KEY ([Author_ID], [Book_ID])
);

-- TABLE PUBLISHER
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[PUBLISHER]') AND type in (N'U'))
DROP TABLE [PUBLISHER]

CREATE TABLE PUBLISHER
(
    Publisher_ID INT IDENTITY(1,1),
    Publisher_name VARCHAR(255),

    PRIMARY KEY ([Publisher_ID])
);

-- TABLE PUBLISH
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[PUBLISH]') AND type in (N'U'))
DROP TABLE [PUBLISH]

CREATE TABLE PUBLISH
(
    Publisher_ID INT,
    Book_ID INT,

    PRIMARY KEY ([Publisher_ID], [Book_ID])
);

-- TABLE GENRE
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[GENRE]') AND type in (N'U'))
DROP TABLE [GENRE]

CREATE TABLE GENRE
(
    Genre_ID INT IDENTITY(1,1),
    Genre_name VARCHAR(255),

    PRIMARY KEY ([Genre_ID])
);

-- TABLE BELONGS_TO
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[BELONGS_TO]') AND type in (N'U'))
DROP TABLE [BELONGS_TO]

CREATE TABLE BELONGS_TO
(
    Genre_ID INT,
    Book_ID INT,

    PRIMARY KEY ([Genre_ID] ,[Book_ID])
);

-- TABLE ACCOUNT
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[ACCOUNT]') AND type in (N'U'))
DROP TABLE [ACCOUNT]

CREATE TABLE ACCOUNT
(
    Account_ID INT IDENTITY(1,1),
    FName VARCHAR(255) NOT NULL,
    LName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    TelephoneNum VARCHAR(15) UNIQUE,
    H_Password VARCHAR(255),--CHECK BY BACKEND
    Birthday DATE,
    Address_M VARCHAR(255),
    Deleted BIT DEFAULT 0,

    PRIMARY KEY ([Account_ID])
);

-- TABLE ROLE
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[ROLE]') AND type in (N'U'))
DROP TABLE [ROLE]

CREATE TABLE ROLE
(
    Role_ID INT,
    Role_name varchar(255) NOT NULL,

    PRIMARY KEY ([Role_ID])
);

-- TABLE REVIEWS
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[REVIEWS]') AND type in (N'U'))
DROP TABLE [REVIEWS]

CREATE TABLE REVIEWS
(
    Create_date DATETIME,
    Account_ID INT,
    Book_ID INT,
    Content VARCHAR(255),
    Ratings INT,
    Img VARCHAR(255),

    PRIMARY KEY ([Create_date], [Account_ID])
);

-- TABLE STAFF
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[STAFF]') AND type in (N'U'))
DROP TABLE [STAFF]

CREATE TABLE STAFF
(
    Staff_ID INT,
    Salary INT,
    Role_ID INT,

    PRIMARY KEY ([Staff_ID])
);

-- TABLE CUSTOMER
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[CUSTOMER]') AND type in (N'U'))
DROP TABLE [CUSTOMER]

CREATE TABLE CUSTOMER
(
    Customer_ID INT,
    Bank_ID VARCHAR(255),
    Bank_name VARCHAR(255),

    PRIMARY KEY ([Customer_ID])
);

-- TABLE SHIPPING_METHOD
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[SHIPPING_METHOD]') AND type in (N'U'))
DROP TABLE [SHIPPING_METHOD]

CREATE TABLE SHIPPING_METHOD
(
    Method_ID INT IDENTITY(1,1),
    Shipping_name VARCHAR(255) NOT NULL,
    Fee INT NOT NULL,

    PRIMARY KEY ([Method_ID])
);

-- TABLE PAYMENT_METHOD
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[PAYMENT_METHOD]') AND type in (N'U'))
DROP TABLE [PAYMENT_METHOD]

CREATE TABLE PAYMENT_METHOD
(
    Payment_ID INT IDENTITY(1,1),
    Payment_name VARCHAR(255),

    PRIMARY KEY ([Payment_ID])
);

-- TABLE ORDER_M
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[ORDER_M]') AND type in (N'U'))
DROP TABLE [ORDER_M]

CREATE TABLE ORDER_M
(
    Order_ID INT IDENTITY(1,1),
    Customer_ID INT,
    Address_M VARCHAR(255),
    Create_date DATE DEFAULT GETDATE(),
    Status_M VARCHAR(255),
    Total_price INT NOT NULL,
    Note TEXT,
    Shipping_ID INT,
    Payment_ID INT,

    PRIMARY KEY ([Order_ID])
)

-- TABLE ORDER_DETAIL
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[ORDER_DETAIL]') AND type in (N'U'))
DROP TABLE [ORDER_DETAIL]

CREATE TABLE ORDER_DETAIL
(
    Detail_ID INT IDENTITY(1,1),
    Order_ID INT NOT NULL,
    Book_ID INT NOT NULL,
    Price INT NOT NULL,
    Quantity INT NOT NULL,
    Total_cost INT NOT NULL,

    PRIMARY KEY ([Detail_ID])
);

-- TABLE CART
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[CART]') AND type in (N'U'))
DROP TABLE [CART]

CREATE TABLE CART
(
    Cart_ID INT IDENTITY(1,1),
    Customer_ID INT,

    PRIMARY KEY ([Cart_ID])
);

-- TABLE CART_DETAIL
GO
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[CART_DETAIL]') AND type in (N'U'))
DROP TABLE [CART_DETAIL]

CREATE TABLE CART_DETAIL
(
    Cart_detail_ID INT IDENTITY(1,1),
    Price INT NOT NULL,
    Quantity INT NOT NULL DEFAULT 0,
    Total_cost INT DEFAULT 0,
    Cart_ID INT,
    Book_ID INT,
    PRIMARY KEY ([Cart_detail_ID])
);

---------------------------create foreign key 

-- TABLE WRITE
GO
ALTER TABLE [WRITE]
WITH CHECK ADD CONSTRAINT fk_write_author_id FOREIGN KEY ([Author_ID]) REFERENCES [AUTHOR]([Author_ID]) 

GO
ALTER TABLE [WRITE]
WITH CHECK ADD CONSTRAINT fk_write_book_id FOREIGN KEY ([Book_ID]) REFERENCES [BOOK]([Book_ID]) 

-- TABLE PUBLISH
GO
ALTER TABLE [PUBLISH]
WITH CHECK ADD CONSTRAINT fk_publish_book_id FOREIGN KEY ([Book_ID]) REFERENCES [BOOK]([Book_ID])

GO
ALTER TABLE [PUBLISH]
WITH CHECK ADD CONSTRAINT fk_publish_publisher_id FOREIGN KEY ([Publisher_ID]) REFERENCES [PUBLISHER]([Publisher_ID])

-- TABLE BELONGS_TO
GO
ALTER TABLE [BELONGS_TO]
WITH CHECK ADD CONSTRAINT fk_belongsto_book_id FOREIGN KEY ([Book_ID]) REFERENCES [BOOK]([Book_ID])

GO
ALTER TABLE [BELONGS_TO]
WITH CHECK ADD CONSTRAINT fk_belongsto_genre_id FOREIGN KEY ([Genre_ID]) REFERENCES [GENRE]([Genre_ID])

-- TABLE STAFF
GO
ALTER TABLE [STAFF]
WITH CHECK ADD CONSTRAINT fk_staff_role_id FOREIGN KEY ([Role_ID]) REFERENCES [ROLE]([Role_ID])

GO
ALTER TABLE [STAFF]
WITH CHECK ADD CONSTRAINT fk_staff_staff_id FOREIGN KEY ([Staff_ID]) REFERENCES [ACCOUNT]([Account_ID])

-- TABLE CUSTOMER
GO
ALTER TABLE [CUSTOMER]
WITH CHECK ADD CONSTRAINT fk_customer_customer_id FOREIGN KEY ([Customer_ID]) REFERENCES [ACCOUNT]([Account_ID])

-- TABLE ORDER_M
GO
ALTER TABLE [ORDER_M]
WITH CHECK ADD CONSTRAINT fk_orderm_customer_id FOREIGN KEY ([Customer_ID]) REFERENCES [CUSTOMER]([Customer_ID])

GO
ALTER TABLE [ORDER_M]
WITH CHECK ADD CONSTRAINT fk_orderm_shipping_id FOREIGN KEY ([Shipping_ID]) REFERENCES [SHIPPING_METHOD]([Method_ID])

GO
ALTER TABLE [ORDER_M]
WITH CHECK ADD CONSTRAINT fk_orderm_payment_id FOREIGN KEY ([Payment_ID]) REFERENCES [PAYMENT_METHOD]([Payment_ID])

-- TABLE ORDER_DETAIL
GO
ALTER TABLE [ORDER_DETAIL]
WITH CHECK ADD CONSTRAINT fk_order_detail_customer_id FOREIGN KEY ([Order_ID]) REFERENCES [ORDER_M]([Order_ID])

GO
ALTER TABLE [ORDER_DETAIL]
WITH CHECK ADD CONSTRAINT fk_order_detail_book_id FOREIGN KEY ([Book_ID]) REFERENCES [BOOK]([Book_ID])

-- TABLE REVIEWS
GO
ALTER TABLE [REVIEWS]
WITH CHECK ADD CONSTRAINT fk_reviews_account_id FOREIGN KEY ([Account_ID]) REFERENCES [ACCOUNT]([Account_ID])

GO
ALTER TABLE [REVIEWS]
WITH CHECK ADD CONSTRAINT fk_reviews_book_id FOREIGN KEY ([Book_ID]) REFERENCES [BOOK]([Book_ID])

-- TABLE CART
GO
ALTER TABLE [CART]
WITH CHECK ADD CONSTRAINT fk_cart_cart_id FOREIGN KEY ([Customer_ID]) REFERENCES [CUSTOMER]([Customer_ID])

-- TABLE CART_DETAIL
GO
ALTER TABLE [CART_DETAIL]
WITH CHECK ADD CONSTRAINT fk_cart_detail_cart_id FOREIGN KEY ([Cart_ID]) REFERENCES [CART]([Cart_ID])

GO
ALTER TABLE [CART_DETAIL]
WITH CHECK ADD CONSTRAINT fk_cart_detail_book_id FOREIGN KEY ([Book_ID]) REFERENCES [BOOK]([Book_ID])

---------------------------thêm các ràng buộc và in lỗi khi INSERT và UPDATE

-- TABLE BOOK
-- Giá gốc (O\_Price) phải lớn hơn 0.
GO
CREATE TRIGGER CheckO_Priceee
ON BOOK
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE O_Price <= 0
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Giá gốc (O_Price) phải lớn hơn 0');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END;

-- Ratings phải nhỏ hơn hoặc bằng 5.
GO
CREATE TRIGGER CheckRatingsBook
ON BOOK
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Ratings > 5)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Ratings phải từ 0 đến 5');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END; 

-- Số lướng sách trong cửa hàng (Quantity) phải lớn hơn hoặc bằng 0.
GO
CREATE TRIGGER CheckQuantityBook
ON BOOK
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Quantity < 0)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Số lượng sách trong cửa hàng (Quantity) phải lớn hơn hoặc bằng 0');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END; 

-- Price được tính toán từ O_Price và Discount để hiển thị giá bán cuối cùng của cuốn sách.
GO
CREATE TRIGGER UpdatePricess
ON BOOK
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE BOOK
    SET Price = (INSERTED.O_Price - INSERTED.O_Price * (CAST(INSERTED.Discount AS FLOAT) / 100.0))
    FROM BOOK
        INNER JOIN INSERTED ON BOOK.Book_ID = INSERTED.Book_ID;
END;


-- Update lại price sau khi update O_Price và Discount
GO
CREATE TRIGGER UpdatePricess_2
ON BOOK
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE BOOK
    SET Price = (INSERTED.O_Price - INSERTED.O_Price * (CAST(INSERTED.Discount AS FLOAT) / 100.0))
    FROM BOOK
        INNER JOIN INSERTED ON BOOK.Book_ID = INSERTED.Book_ID;
END;

-- Giá bán (Price) và phần trăm khuyến mãi (Discount) phải lớn hơn hoặc bằng 0.
GO
CREATE TRIGGER CheckDiscountBook
ON BOOK
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Discount < 0)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Phần trăm khuyến mãi (Discount) phải lớn hơn hoặc bằng 0.');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END; 

-- Năm xuất bản của quyển sách phải nhỏ hơn hoặc bằng năm hiện tại.
GO
CREATE TRIGGER CheckPublish_yearBook
ON BOOK
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE ([Publish_year] >  YEAR(GETDATE()))
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Năm xuất bản của quyển sách(Publish_year) phải nhỏ hơn hoặc bằng năm hiện tại.');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END; 

-- TABLE ACCOUNT
-- Lấy số năm hiện tại trừ đi số năm trong Birthday kết quả phải lớn hơn hoặc bằng 18.
GO
CREATE TRIGGER CheckBirthdayAccount
ON ACCOUNT
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (
        SELECT 1
    FROM INSERTED
    WHERE DATEDIFF(YEAR, Birthday, GETDATE()) < 18
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Bạn chưa đủ tuổi, bạn phải từ 18 tuổi.');
        ROLLBACK TRANSACTION;
    END;
END;

-- Password là một chuỗi có ít nhất một chữ in hoa, một số và một ký tự đặc biệt (!, @, #).
-- BACKEND
-- GO
-- CREATE OR ALTER TRIGGER CheckPasswordAccount
-- ON ACCOUNT
-- AFTER INSERT
-- AS
-- BEGIN
--     SET NOCOUNT ON;

--     IF EXISTS (
--         SELECT 1
--         FROM INSERTED
--         WHERE H_Password NOT LIKE '%[A-Z]%' 
--         OR H_Password NOT LIKE '%[0-9]%' 
--         OR H_Password NOT LIKE '%[!@#]%' 
--     )
--     BEGIN
--         RAISERROR(14138, -1, -1, N'Password là một chuỗi có ít nhất một chữ in hoa, một số và một ký tự đặc biệt (!, @, #)');
--         ROLLBACK TRANSACTION;
--     END;
-- END;

-- TABLE STAFF
-- Salary phải lớn hơn 0.
GO
CREATE TRIGGER CheckSalaryStaff
ON STAFF
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Salary <= 0)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Salary phải lớn hơn 0.');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END; 

-- TABLE REVIEWS
-- Giá trị của Ratings phải là một số nguyên từ 1 đến 5.
GO
CREATE TRIGGER CheckRatingsReviews
ON REVIEWS
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Ratings > 5 AND Ratings < 1)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Ratings phải từ 1 đến 5');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END;

-- TABLE CART_DETAIL
-- Quantity phải lớn hơn 0.
GO
CREATE TRIGGER CheckQuantityCartDetail
ON CART_DETAIL
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Quantity <= 0)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Số lượng sách (Quantity) phải lớn hơn 0');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END; 

-- Tính Total_cost = Price x Quantity
GO
CREATE TRIGGER CalTotalCostCartDetail
ON CART_DETAIL
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE CART_DETAIL
    SET Total_cost = INSERTED.Price * INSERTED.Quantity
    FROM CART_DETAIL
        INNER JOIN INSERTED ON CART_DETAIL.Cart_detail_ID = INSERTED.Cart_detail_ID;
END;

--- Update Lại CART_DETAIL.Total_cost sau khi Price và Quantity update--
GO
CREATE TRIGGER UpdateTotalCostCartDetail
ON CART_DETAIL
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE CART_DETAIL
    SET Total_cost = INSERTED.Price * INSERTED.Quantity
    FROM CART_DETAIL
        INNER JOIN INSERTED ON CART_DETAIL.Cart_detail_ID = INSERTED.Cart_detail_ID;
END;

-- TABLE ORDER
-- Create_date phải nhỏ hơn hoặc bằng ngày giờ hiện tại.
-- Total_price phải lớn hơn 0.
GO
CREATE TRIGGER CheckTotalPriceOrder
ON ORDER_M
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Total_price <= 0)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Tổng hoá đơn (Total_price) phải lớn hơn 0.');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END; 

-- Status chỉ có bốn giá trị tương ứng: 0 - Đang xử lý (Processing), 1 - Đang vận chuyển
-- (Delivering), 2 - Vận chuyển thành công (Completed), 3 - Đơn hàng bị hủy (Canceled).
GO
CREATE TRIGGER CheckStatusOrder
ON ORDER_M
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (
        SELECT 1
    FROM INSERTED i
        LEFT JOIN sys.columns c ON OBJECT_ID('ORDER_M') = c.object_id
    WHERE c.name = 'Status_M'
        AND i.Status_M NOT IN ('0', '1','2','3')
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Status chỉ có bốn giá trị tương ứng: 0 - Đang xử lý (Processing), 1 - Đang vận chuyển (Delivering), 2 - Vận chuyển thành công (Completed), 3 - Đơn hàng bị hủy (Canceled).');
        ROLLBACK TRANSACTION;
    END;
END;

-- TABLE ORDER_DETAIL
-- Quantity phải lớn hơn 0.
GO
CREATE TRIGGER CheckQuantityOrderDetail
ON ORDER_DETAIL
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Quantity <= 0)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Số lượng sách (Quantity) phải lớn hơn 0');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END;

-- Total_cost và Price phải lớn hơn hoặc bằng 0.
GO
CREATE TRIGGER CheckPriceOrderDetail
ON ORDER_DETAIL
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1
    FROM inserted
    WHERE (Price < 0)
    )
    BEGIN
        RAISERROR(14138, -1, -1, N'Giá sách (Price) phải lớn hơn hoặc bằng 0');
        ROLLBACK TRANSACTION;
        RETURN;
    END
END;

-- Tính Total_cost = Price x Quantity
GO
CREATE TRIGGER CalTotalCostOrderDetail
ON ORDER_DETAIL
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE ORDER_DETAIL
    SET Total_cost = INSERTED.Price * INSERTED.Quantity
    FROM ORDER_DETAIL
        INNER JOIN INSERTED ON ORDER_DETAIL.Detail_ID = INSERTED.Detail_ID;
END;

---------------------------import data



--------------------------------END-------------------------------------
--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
----------------------------------------------------------------------------
-- Disable all CONSTRAINTS
EXEC sp_msforeachtable "ALTER TABLE ? NOCHECK CONSTRAINT ALL";
-- Import data
-- Enable all CONSTRAINTS
EXEC sp_msforeachtable "ALTER TABLE ? WITH CHECK CHECK CONSTRAINT ALL";
----------------------------------------------------------------------------
--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////