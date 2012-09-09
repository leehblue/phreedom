drop table if exists `contacts`;
drop table if exists `addresses`;

create table if not exists `contacts` (
  `id` int(11) unsigned not null auto_increment,
  `first_name` varchar(50) not null default '',
  `last_name` varchar(50) not null default '',
  primary key (`id`)
) engine=innodb default charset=latin1;

create table if not exists `addresses` (
  `id` int(11) unsigned not null auto_increment,
  `contact_id` int(11) unsigned not null,
  `street` varchar(50) not null default '',
  `city` varchar(50) not null default '',
  `state` varchar(50) not null default '',
  `zip` varchar(50) not null default '',
  primary key (`id`)
) engine=innodb default charset=latin1;

insert into `contacts` (id, first_name, last_name) values (1, 'Lee', 'Blue');
insert into `contacts` (id, first_name, last_name) values (2, 'Emily', 'Blue');
insert into `contacts` (id, first_name, last_name) values (3, 'Kendall', 'Blue');
insert into `contacts` (id, first_name, last_name) values (4, 'Micah', 'Blue');
insert into `contacts` (id, first_name, last_name) values (5, 'Trinity', 'Blue');
insert into `contacts` (id, first_name, last_name) values (6, 'Lily', 'Blue');
insert into `contacts` (id, first_name, last_name) values (7, 'Joey', 'Beninghove');
insert into `contacts` (id, first_name, last_name) values (8, 'Bobby', 'Smith');
insert into `contacts` (id, first_name, last_name) values (9, 'Andre', 'Fredette');
insert into `contacts` (id, first_name, last_name) values (10, 'Benjamin', 'Rojas');
insert into `contacts` (id, first_name, last_name) values (11, 'David', 'Rojas');
insert into `contacts` (id, first_name, last_name) values (12, 'Rain', 'Dog');
  
insert into `addresses` (id, contact_id, street, city, state, zip) values (1, 1, '1001 Test Dr', 'Lanexa', 'VA', '23089');
insert into `addresses` (id, contact_id, street, city, state, zip) values (2, 2, '1002 Test Dr', 'Lanexa', 'VA', '23089');
insert into `addresses` (id, contact_id, street, city, state, zip) values (3, 3, '1003 Test Dr', 'Lanexa', 'VA', '23089');
insert into `addresses` (id, contact_id, street, city, state, zip) values (4, 4, '1004 Test Dr', 'Lanexa', 'VA', '23089');