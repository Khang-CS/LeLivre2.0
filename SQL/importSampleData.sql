-- TABLE BOOK

USE DBMSAssignmentOrderBook
GO

INSERT INTO BOOK
    (Book_name, O_Price, Discount, Publish_year, Description, Ratings, Thumbnail, Reviews_N, Quantity)
VALUES
    ('To Kill a Mockingbird', 200000, 0, 1960, 'A classic novel by Harper Lee', 5, 'mockingbird.jpg', 500, 50),
    ('1984', 250000, 10, 1949, 'A dystopian novel by George Orwell', 4, '1984.jpg', 300, 50),
    ('The Great Gatsby', 180000, 5, 1925, 'A novel by F. Scott Fitzgerald', 5, 'gatsby.jpg', 450, 90),
    ('Pride and Prejudice', 220000, 8, 1813, 'A novel by Jane Austen', 5, 'pride.jpg', 600, 12),
    ('Moby Dick', 300000, 15, 1851, 'A novel by Herman Melville', 4, 'mobydick.jpg', 250, 500),
    ('War and Peace', 280000, 0, 1869, 'A novel by Leo Tolstoy', 5, 'warandpeace.jpg', 350, 80),
    ('The Catcher in the Rye', 240000, 10, 1951, 'A novel by J.D. Salinger', 4, 'catcher.jpg', 400, 50),
    ('Brave New World', 230000, 8, 1932, 'A novel by Aldous Huxley', 4, 'bravenewworld.jpg', 280, 60),
    ('Frankenstein', 210000, 0, 1818, 'A novel by Mary Shelley', 4, 'frankenstein.jpg', 320, 70),
    ('The Hobbit', 260000, 10, 1937, 'A fantasy novel by J.R.R. Tolkien', 5, 'hobbit.jpg', 550, 10);

-- TABLE AUTHOR
INSERT INTO AUTHOR
    (Author_name)
VALUES
    ('Harper Lee'),
    ('George Orwell'),
    ('F. Scott Fitzgerald'),
    ('Jane Austen'),
    ('Herman Melville'),
    ('Leo Tolstoy'),
    ('J.D. Salinger'),
    ('Aldous Huxley'),
    ('Mary Shelley'),
    ('J.R.R. Tolkien');
