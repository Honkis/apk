drop database if exists apk;
CREATE DATABASE apk;
use apk;

create table artiklar (
	nr				int not null unique auto_increment,
	artikelid		varchar(10) not null,
	varunr			varchar(10) not null,
	namn			varchar(50),
	namn2			varchar(50),
	varugrupp		varchar(30),
	forpackning		varchar(30),
	pris			decimal(11,2) not null,
	alkoholhalt		decimal(6,3) not null,
	volym			int(7) not null,
	apk				decimal(8,2) not null,
	primary key(nr)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

create table butik (
	butiknr		int(4) not null,
	gatuadress 	varchar(20),
	stad		varchar(15),
	postnr		varchar(8),
	lan			varchar(20),
	primary key(butiknr)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

create table artikel_butik (
	butiknr		int(4) not null,
	nr			int not null unique,
	primary key(butiknr,nr),
	foreign key(butiknr) references butik(butiknr),
	foreign key(nr) references artiklar(nr)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;
