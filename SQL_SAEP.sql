create database SAEP;
use SAEP;

create table Tbl_Usuarios(
       ID INT Primary Key AUTO_INCREMENT,
       Nome Varchar(50),
       Email varchar(50)
);

create table Tbl_Tarefas (
       ID INT Primary Key AUTO_INCREMENT,
       Descricao Varchar(100),
       Setor Varchar(50),
       Prioridade Varchar(50),
       Status Varchar(50),
       Data date,
       usuario_Id int,
       foreign key(usuario_Id) references Tbl_Usuarios(ID)     
);