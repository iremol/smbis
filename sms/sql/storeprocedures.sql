--type

create type ty_studentinfo as (vappid varchar(100),c_fn varchar(100),c_sn varchar(100),c_gender varchar(10), c_dob date, c_addr varchar(255), c_pic varchar(255),g_title varchar(100), g_sn varchar(100), g_fn varchar(100), g_addr varchar(255), g_pc varchar(10), g_email varchar(33), g_dtno varchar(20), g_rel varchar(20), m_cond varchar(255), e_tn varchar(255), m_pnum varchar(20), e_addr varchar(255), vstudent varchar(100), vclass varchar(100), dtstamp timestamp with time zone );
--create stored procedures in postgre sql

--searchStudent(varchar(100)
create or replace function searchStudent(stno varchar(100))
returns ty_studentinfo as $$
declare
row ty_studentinfo;
begin
select * into row from studentinfo_v where vstudent=stno;
return row;
end;
$$ language plpgsql;

select * from searchStudent('R2018ECCD958');

--searchStudent(varchar(100), varchar(100))

create or replace function searchStudent(fn varchar(100), ln varchar(100))
returns setof ty_studentinfo as $$
declare 
row ty_studentinfo;
begin
return query select * from studentinfo_v where c_fn like fn || '%' and c_sn like ln || '%';
end;
$$ language plpgsql;

--updateStudent(vstudent varchar(100), varchar(100)),
create or replace function updateGuardianPhnum(g_info varchar(100), newPhNum varchar(20)) returns integer as $$
declare
begin                 
return update guardianinfo set vdaytime_no=newPhNum where vguardian_info=g_info; 
end;                                                           
$$ language plpgsql;

create or replace function updateStudentClass( stNo varchar(100), newClass varchar(5)) returns void as $$
update teaching.student  set vclass=newClass where vstudent=stNo;                                                            
$$ language sql;

-- updateGuardianAddr(varchar, varchar)
create or replace function updateGuardianAddr(studentid varchar(100), newAddr varchar(255)) returns void as $$
declare
appid varchar(100);
gid varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = studentid;
select vguardian_info into gid from application where vapplication = appid
update guardianinfo set vaddress=newAddr where vguardian_info = gid;
end;                                                            
$$ language plpgsql;

--updateGuardian(varchar, varchar, varchar, varchar);
create or replace function updateGuardian(studentid varchar(100), newAddr varchar(255), newEmail varchar(255), newPhone varchar(20)) returns void as $$
declare
appid varchar(100);
gid varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = studentid;
select vguardian_info into gid from application where vapplication = appid
update guardianinfo set vaddress=newAddr, vemail=newEmail, vdaytime_no=newPhone where vguardian_info = gid;
end;                                                            
$$ language plpgsql;

--updateStudent(varchar, varchar, varchar, varchar);
create or replace function updateStudent(studentid varchar(100), newAddr varchar(255),newClass varchar(100)) returns void as $$
declare
appid varchar(100);
binfo varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = studentid;
select vbio_info into binfo from application where vapplication = appid;
update bioinfo set vaddress=newAddr where vbio_info = binfo;
update teaching.student set vclass=newClass where vstudent = studentid;
end;                                                            
$$ language plpgsql;

create or replace function updateGuardianEmail(oldEmail varchar(100), newEmail varchar(100)) returns void as $$
update guardianinfo set vemail=newEmail where vemail=oldEmail;                                                            
$$ language sql;

create or replace function updateGuardianPhnum(oldPn varchar(20), newPn varchar(20)) returns void as $$
update guardianinfo set vdaytime_no=newPn where vdaytime_no=oldPn;                                                            
$$ language sql;
-- updateStudentAddress(varchar, varchar)
create or replace function updateStudentAddress(stNo varchar(100), newAddr varchar(255)) returns void as $$
declare 
appid varchar(100);
bioinfoid varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = stNo;
select vbio_info into bioinfoid from application where vapplication = appid;
update bioinfo  set vaddress=newAddr where vbio_info = bioinfoid;
end;                                                            
$$ language plpgsql;


-- searchEmp(varchar)
create or replace function searchEmp(empno varchar(100)) returns hr.employee as $$
declare
row hr.employee%ROWTYPE
begin
select * into row from hr.employee where vemployee = empno;
return row;
end;
$$ language plpgsql;

-- searchEmp(varchar,varchar)
create or replace function searchEmp(fn varchar(20), ln varchar(20)) returns hr.employee as $$
select * from hr.employee where vfirstname like fn || '%' or vlastname like ln || '%';
$$ language sql;


--updateAddress(varchar(255), varchar(255))
create or replace function updateEmpAddress(empno varchar(100) , newaddr1 varchar(255), newaddr2 varchar(255) ) returns void as $$  
update hr.employee set vaddress_one = newaddr1, vaddress_two = newaddr2 where vemployee = empno;
$$ language sql;

--getTeachers -- sample function
create or replace function getTeachers() returns setof hr.employee as $$
select e.vemployee from hr.employee e , hr.position p  where e.vposition = p.vposition and vdescription = 'Teacher';
$$ language sql;