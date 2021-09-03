-- CREATE TABLES --

CREATE TABLE staff (
    staff_ID INT,
    staff_name VARCHAR(30),
    join_date date,

    PRIMARY KEY(staff_ID)
);

CREATE TABLE branch_location (
    branch_name VARCHAR(50),
    phone_num VARCHAR(20),
    address VARCHAR(30),

    PRIMARY KEY (branch_name)
);

CREATE TABLE branch_information (
    branch_ID INT,
    branch_name VARCHAR(50),
    staff_ID INT,

    PRIMARY KEY (branch_ID),
    FOREIGN KEY (staff_ID) REFERENCES staff(staff_ID),
    FOREIGN KEY (branch_name) REFERENCES branch_location(branch_name) ON DELETE CASCADE
);

CREATE TABLE floor_isin (
    floor_num INT,
    room_counter INT,
    branch_ID INT,

    PRIMARY KEY (floor_num, branch_ID),
    FOREIGN KEY (branch_ID) REFERENCES branch_information(branch_ID) ON DELETE CASCADE
);

CREATE TABLE room_ison (
    room_ID INT,
    room_num INT,
    bed_counter INT,
    floor_num INT,

    PRIMARY KEY (room_ID, floor_num),
    FOREIGN KEY (floor_num) REFERENCES floor_isin(floor_num) ON DELETE CASCADE
);

CREATE TABLE guest_info (
    guest_name VARCHAR(30),
    party_size INT,
    check_in DATE,
    check_out DATE,

    PRIMARY KEY (guest_name)
);

CREATE TABLE room_info (
    guest_name VARCHAR(30),
    room_ID INT,

    PRIMARY KEY (guest_name, room_ID),
    FOREIGN KEY (guest_name) REFERENCES guest_info(guest_name) ON DELETE CASCADE,
    FOREIGN KEY (room_ID) REFERENCES room_ison(room_ID) ON DELETE CASCADE
);

CREATE TABLE keyholders (
    first_keyholder VARCHAR(30),
    second_keyholder VARCHAR(30),

    PRIMARY KEY (first_keyholder, second_keyholder),
    FOREIGN KEY (first_keyholder) REFERENCES guest_info(guest_name),
    FOREIGN KEY (second_keyholder) REFERENCES guest_info(guest_name)
);




-- INSERT TABLES --

INSERT INTO staff VALUES
(100, 'Yeji Hwang', '2000/05/12'),
(101, 'Jisu Choi', '2001/09/03'),
(102, 'Ryujin Shin', '2000/10/08'),
(103, 'Chaeryoung Lee', '2010/12/01'),
(104, 'Yuna Shin', '2009/08/21');

INSERT INTO branch_location VALUES
('Good Hotel Vancouver Waterfront', '604123654', '123 Good Place'),
('Good Hotel Vancouver Downtown', '6049876543', '123 Keefer Place'),
('Good Hotel Seattle Downtown', '2061234567', '123 Pike Place'),
('Good Hotel Seattle Waterfront', '2065432109', '333 Roses Ave'),
('Good Hotel Tokyo', '‘033333333', '123 Anime Street');

INSERT INTO branch_information VALUES
(69, 'Good Hotel Vancouver Waterfront', 100),
(70, 'Good Hotel Vancouver Downtown', 101),
(71, 'Good Hotel Seattle Downtown', 102),
(72, 'Good Hotel Seattle Waterfront', 103),
(73, 'Good Hotel Tokyo', 104);

-- All floors in All branches
INSERT INTO floor_isin VALUES
('6901', '20', 69), -- Floor 1 of branch 69
('6902', '20', 69),
('6903', '20', 69),
('7001', '20', 70), -- Floor 1 of branch 70
('7002', '20', 70),
('7003', '20', 70),
('7004', '20', 70),
('7005', '20', 70),
('7101', '20', 71), -- Floor 1 of branch 71
('7102', '20', 71),
('7103', '20', 71),
('7104', '20', 71),
('7201', '20', 72), -- Floor 1 of branch 72
('7202', '20', 72), 
('7203', '20', 72),
('7301', '20', 73), -- Floor 1 of branch 73
('7302', '20', 73),
('7303', '20', 73);

-- All rooms in All floors 
INSERT INTO room_ison VALUES
('690101', '101', '2', '6901'), -- ID: 690101; NUM: 101; FLOOR: 1;
('690103', '103', '2', '6901'),
('690105', '105', '2', '6901'),
('690203', '203', '2', '6902'),
('690210', '210', '2', '6902'),
('690215', '215', '2', '6902'),
('690301', '301', '2', '6903'),
('690320', '320', '2', '6903'),
('700101', '101', '2', '7001'), -- ID: 700101; NUM: 101; FLOOR: 1;
('700102', '102', '2', '7001'),
('700215', '215', '2', '7002'),
('700309', '309', '2', '7003'),
('700411', '411', '2', '7004'),
('700503', '503', '2', '7005'),
('710101', '101', '2', '7101'), -- ID: 710101; NUM: 101; FLOOR: 1;
('710219', '219', '2', '7102'),
('710307', '307', '2', '7103'),
('710416', '416', '2', '7104'),
('720102', '102', '2', '7201'), -- ID: 720101; NUM: 101; FLOOR: 1;
('720211', '211', '2', '7202'),
('720302', '302', '2', '7203'),
('730110', '110', '2', '7301'), -- ID: 690101; NUM: 101; FLOOR: 1;
('730202', '202', '2', '7302'),
('730319', '319', '2', '7303');

INSERT INTO guest_info VALUES
('Jaren', '3', '2020/10/03', '2020/10/04'),
('Brian', '2', '2020/11/01', '2020/11/07'),
('Juliane', '2', '2020/11/01', '2020/11/07'),
('Karin', '2', '2020/11/2', '2020/11/05'),
('Andrew', '2', '2020/11/2', '2020/11/05'),
('Sosa', '3', '2020/10/03', '2020/10/04'),
('NBAYoung Boy', '2', '2021/04/22', '2021/04/25'),
('JI', '2', '2021/04/22', '2021/04/25'),
('Pui Yi', '2', '2021/05/13', '2021/05/18'),
('Rick', '2', '2021/05/13', '2021/05/18');

INSERT INTO room_info VALUES
('Karin', '720102'),
('NBAYoung Boy', '690320'),
('Brian', '730319'),
('Jaren', '690105'),
('Pui Yi', '700215');

INSERT INTO keyholders VALUES
('Karin', 'Andrew'),
('NBAYoung Boy', 'JI'),
('Brian', 'Juliane'),
('Jaren', 'Sosa'),
('Pui Yi', 'Rick');
