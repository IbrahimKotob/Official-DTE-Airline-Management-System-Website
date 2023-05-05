-- Create Seat table
CREATE TABLE Seat (
  seatID INT PRIMARY KEY,
  flightID INT,
  type VARCHAR(255),
  seatClass VARCHAR(50),
  seatNumber VARCHAR(10),
  occupied BOOLEAN,
  FOREIGN KEY (flightID) REFERENCES Flight(flightID)
);

-- Create Payment table
CREATE TABLE Payment (
  paymentID INT AUTO_INCREMENT PRIMARY KEY,
  fee DECIMAL(10, 2),
  status VARCHAR(50)
);
-- Create Notifications table
CREATE TABLE Notifications (
  id INT PRIMARY KEY,
  date DATE,
  content VARCHAR(255),
  accountID INT,
  FOREIGN KEY (accountID) REFERENCES Account(PersonID)
);
CREATE TABLE Flight (
  flightID INT AUTO_INCREMENT  PRIMARY KEY,
  departureAirportID INT,
  arrivalAirportID INT,
  departureTime DATETIME,
  returnTime DATETIME,
  gate VARCHAR(20),
  status VARCHAR(50),
  aircraftID INT,
  pilotID INT,
  Co-PilotID INT,
  FOREIGN KEY (departureAirportID) REFERENCES Airport(airportID),
  FOREIGN KEY (arrivalAirportID) REFERENCES Airport(airportID),
  FOREIGN KEY (aircraftID) REFERENCES Aircraft(aircraftID)
);
-- Create Aircraft table
CREATE TABLE Aircraft (
  aircraftID INT PRIMARY KEY,
  name VARCHAR(255),
  model VARCHAR(255),
  manufacturingYear INT
);
-- Create Airport table
CREATE TABLE Airport (
  airportID INT PRIMARY KEY,
  name VARCHAR(255),
  address VARCHAR(255)
);

-- Create FlightReservation table
CREATE TABLE FlightReservation (
  reservationNumber INT PRIMARY KEY,
  meals VARCHAR(255),
  CustomerID INT,
  flightID INT,
  seatID INT,
  paymentID INT,
 SpecialNeeds  BOOLEAN,
  reservation DATETIME,
  FOREIGN KEY (CustomerID) REFERENCES Customer(customerID),
  FOREIGN KEY (flightID) REFERENCES Flight(flightID),
  FOREIGN KEY (seatID) REFERENCES Seat(seatID),
  FOREIGN KEY (paymentID) REFERENCES Payment(paymentID)
);
-- Create Person table
CREATE TABLE Person (
  personID INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  address VARCHAR(255),
  email VARCHAR(255),
  phone VARCHAR(20)
);

-- Create Account table
CREATE TABLE Account (
  accountID INT AUTO_INCREMENT PRIMARY KEY,
  personID INT,
  password VARCHAR(255),
  status VARCHAR(50),
  level VARCHAR(50),
  FOREIGN KEY (personID) REFERENCES Person(personID)
);
CREATE TABLE Pilot (
  PilotID INT AUTO_INCREMENT PRIMARY KEY,
  accountID INT ,
  FlightID INT,
   FirstName VARCHAR(255),
    LastName VARCHAR(255),
    DOB DATE,
    Age INT,
    Email VARCHAR(255),
    Username VARCHAR(255),
    Password VARCHAR(255),
    Address VARCHAR(255),
    PhoneNumber VARCHAR(20),
    City VARCHAR(255),
    Country VARCHAR(255),
    PostalCode VARCHAR(20),
  FOREIGN KEY (accountID) REFERENCES Account(AccountID),
  FOREIGN KEY (FlightID) REFERENCES Flight(FlightID)
);
CREATE TABLE Crew_Member (
    CrewID INT AUTO_INCREMENT PRIMARY KEY,
  accountID INT ,
  FlightID INT,
   FirstName VARCHAR(255),
    LastName VARCHAR(255),
    DOB DATE,
    Age INT,
    Email VARCHAR(255),
    Username VARCHAR(255),
    Password VARCHAR(255),
    Address VARCHAR(255),
    PhoneNumber VARCHAR(20),
    City VARCHAR(255),
    Country VARCHAR(255),
    PostalCode VARCHAR(20),
  FOREIGN KEY (accountID) REFERENCES Account(AccountID),
  FOREIGN KEY (FlightID) REFERENCES Flight(FlightID)
);
CREATE TABLE Customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
      accountID INT ,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    DOB DATE,
    Age INT,
    Email VARCHAR(255),
    Username VARCHAR(255),
    Password VARCHAR(255),
    Address VARCHAR(255),
    PhoneNumber VARCHAR(20),
    City VARCHAR(255),
    Country VARCHAR(255),
    PostalCode VARCHAR(20),
    SpecialNeeds BOOLEAN NULL,
     FOREIGN KEY (accountID) REFERENCES Account(AccountID)
);

-- Grant SELECT privilege to Pilot and Crew to viewAssignedFlight
GRANT SELECT ON Flight TO Pilot;
GRANT SELECT ON Flight TO Crew;

-- Grant DELETE, INSERT, UPDATE privileges to Admin for various actions
GRANT DELETE ON Flight TO Admin;
GRANT INSERT ON Flight TO Admin;
GRANT UPDATE(departureTime) ON Flight TO Admin;
GRANT UPDATE(gate, status, aircraft) ON Flight TO Admin;
GRANT UPDATE(pilotID) ON Flight TO Admin;
GRANT UPDATE(crewID) ON Flight TO Admin;

-- Grant EXECUTE privilege to Admin for assignPilot and assignCrew procedures
GRANT EXECUTE ON FUNCTION assignPilot TO Admin;
GRANT EXECUTE ON FUNCTION assignCrew TO Admin;

-- Grant ALL privileges to Admin for editing flight
GRANT ALL ON Flight TO Admin;

-- Grant INSERT privilege to FrontDeskOfficer to createReservation
GRANT INSERT ON FlightReservation TO FrontDeskOfficer;

-- Grant SELECT privilege to FrontDeskOfficer to viewAvailableSeats
GRANT SELECT ON Seat TO FrontDeskOfficer;

-- Grant UPDATE privilege to FrontDeskOfficer to processPayment
GRANT UPDATE ON FlightReservation TO FrontDeskOfficer;

-- Grant EXECUTE privilege to FrontDeskOfficer for printTicket procedure
GRANT EXECUTE ON FUNCTION printTicket TO FrontDeskOfficer;

-- Grant SELECT privilege to Customer to getReservation
GRANT SELECT ON FlightReservation TO Customer;

-- Grant INSERT privilege to Customer to bookFlight
GRANT INSERT ON FlightReservation TO Customer;

-- Grant EXECUTE privilege to Customer for makePayment procedure
GRANT EXECUTE ON FUNCTION makePayment TO Customer;
-- Create a trigger to enforce capacity constraint
CREATE TRIGGER check_capacity
BEFORE INSERT ON FlightReservation
FOR EACH ROW
BEGIN
    DECLARE flight_capacity INT;
    
    -- Get the capacity of the flight being reserved
    SELECT capacity INTO flight_capacity
    FROM Flight
    WHERE flightID = NEW.flightID;
    
    -- Check if the flight capacity is greater than 50
    IF flight_capacity <= 50 THEN
        -- Raise an error if capacity constraint is violated
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Flight capacity must be greater than 50 to allow reservations.';
    END IF;
END;


