--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.3
-- Dumped by pg_dump version 9.6.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: admission; Type: SCHEMA; Schema: -; Owner: sms_admin
--

CREATE SCHEMA admission;


ALTER SCHEMA admission OWNER TO sms_admin;

--
-- Name: facts; Type: SCHEMA; Schema: -; Owner: sms_admin
--

CREATE SCHEMA facts;


ALTER SCHEMA facts OWNER TO sms_admin;

--
-- Name: hr; Type: SCHEMA; Schema: -; Owner: sms_admin
--

CREATE SCHEMA hr;


ALTER SCHEMA hr OWNER TO sms_admin;

--
-- Name: predef; Type: SCHEMA; Schema: -; Owner: root
--

CREATE SCHEMA predef;


ALTER SCHEMA predef OWNER TO root;

--
-- Name: teaching; Type: SCHEMA; Schema: -; Owner: sms_admin
--

CREATE SCHEMA teaching;


ALTER SCHEMA teaching OWNER TO sms_admin;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: ty_studentinfo; Type: TYPE; Schema: public; Owner: sms_admin
--

CREATE TYPE ty_studentinfo AS (
	vappid character varying(100),
	c_fn character varying(100),
	c_sn character varying(100),
	c_gender character varying(10),
	c_dob date,
	c_addr character varying(255),
	c_pic character varying(255),
	g_title character varying(100),
	g_sn character varying(100),
	g_fn character varying(100),
	g_addr character varying(255),
	g_pc character varying(10),
	g_email character varying(33),
	g_dtno character varying(20),
	g_rel character varying(20),
	m_cond character varying(255),
	e_tn character varying(255),
	m_pnum character varying(20),
	e_addr character varying(255),
	vstudent character varying(100),
	vclass character varying(100),
	dtstamp timestamp with time zone
);


ALTER TYPE ty_studentinfo OWNER TO sms_admin;

SET search_path = hr, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: employee; Type: TABLE; Schema: hr; Owner: sms_admin
--

CREATE TABLE employee (
    vemployee character varying(100) NOT NULL,
    vfirstname character varying(50),
    vothername character varying(50),
    vlastname character varying(50),
    vaddress_one character varying(255),
    vaddress_two character varying(255),
    vstateoforigin character varying(20),
    vcountry character varying(50),
    ddateofemp date,
    vdepartment character varying(20),
    vposition character varying(20) NOT NULL,
    vpicture character varying(255) NOT NULL
);


ALTER TABLE employee OWNER TO sms_admin;

SET search_path = public, pg_catalog;

--
-- Name: getteachers(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION getteachers() RETURNS SETOF hr.employee
    LANGUAGE sql
    AS $$
select e.* from hr.employee e , hr.position p  where e.vposition = p.vposition and vdescription = 'Teacher';
$$;


ALTER FUNCTION public.getteachers() OWNER TO root;

--
-- Name: searchemp(character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION searchemp(empno character varying) RETURNS hr.employee
    LANGUAGE plpgsql
    AS $$
declare
row hr.employee%ROWTYPE;
begin
select * into row from hr.employee where vemployee = empno;
return row;
end;
$$;


ALTER FUNCTION public.searchemp(empno character varying) OWNER TO sms_admin;

--
-- Name: searchemp(character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION searchemp(fn character varying, ln character varying) RETURNS hr.employee
    LANGUAGE sql
    AS $$
select * from hr.employee where vfirstname like fn || '%' and vlastname like ln || '%';
$$;


ALTER FUNCTION public.searchemp(fn character varying, ln character varying) OWNER TO sms_admin;

--
-- Name: searchstudent(character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION searchstudent(stno character varying) RETURNS ty_studentinfo
    LANGUAGE plpgsql
    AS $$
declare
row ty_studentinfo;
begin
select * into row from studentinfo_v where vstudent=stno;
return row;
end;
$$;


ALTER FUNCTION public.searchstudent(stno character varying) OWNER TO sms_admin;

--
-- Name: searchstudent(character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION searchstudent(fn character varying, ln character varying) RETURNS SETOF ty_studentinfo
    LANGUAGE plpgsql
    AS $$
declare 
row ty_studentinfo;
begin
return query select * from studentinfo_v where c_fn like fn || '%' and c_sn like ln || '%';
end;
$$;


ALTER FUNCTION public.searchstudent(fn character varying, ln character varying) OWNER TO sms_admin;

--
-- Name: updateempaddress(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updateempaddress(empno character varying, newaddr1 character varying, newaddr2 character varying) RETURNS void
    LANGUAGE sql
    AS $$  
update hr.employee set vaddress_one = newaddr1, vaddress_two = newaddr2 where vemployee = empno;
$$;


ALTER FUNCTION public.updateempaddress(empno character varying, newaddr1 character varying, newaddr2 character varying) OWNER TO sms_admin;

--
-- Name: updateguardian(character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updateguardian(studentid character varying, newaddr character varying, newemail character varying, newphone character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
appid varchar(100);
gid varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = studentid;
select vguardian_info into gid from application where vapplication = appid;
update guardianinfo set vaddress=newAddr, vemail=newEmail, vdaytime_no=newPhone where vguardian_info = gid;
end;                                                            
$$;


ALTER FUNCTION public.updateguardian(studentid character varying, newaddr character varying, newemail character varying, newphone character varying) OWNER TO sms_admin;

--
-- Name: updateguardianaddr(character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updateguardianaddr(studentid character varying, newaddr character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
appid varchar(100);
gid varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = studentid;
select vguardian_info into gid from application where vapplication = appid;
update guardianinfo set vaddress=newAddr where vguardian_info = gid;
end;                                                            
$$;


ALTER FUNCTION public.updateguardianaddr(studentid character varying, newaddr character varying) OWNER TO sms_admin;

--
-- Name: updateguardianemail(character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updateguardianemail(oldemail character varying, newemail character varying) RETURNS void
    LANGUAGE sql
    AS $$
update guardianinfo set vemail=newEmail where vemail=oldEmail;                                                            
$$;


ALTER FUNCTION public.updateguardianemail(oldemail character varying, newemail character varying) OWNER TO sms_admin;

--
-- Name: updateguardianphnum(character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updateguardianphnum(oldpn character varying, newpn character varying) RETURNS void
    LANGUAGE sql
    AS $$
update guardianinfo set vdaytime_no=newPn where vdaytime_no=oldPn;                                                            
$$;


ALTER FUNCTION public.updateguardianphnum(oldpn character varying, newpn character varying) OWNER TO sms_admin;

--
-- Name: updatestudent(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updatestudent(studentid character varying, newaddr character varying, newclass character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
appid varchar(100);
binfo varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = studentid;
select vbio_info into binfo from application where vapplication = appid;
update bioinfo set vaddress=newAddr where vbio_info = binfo;
update teaching.student set vclass=newClass where vstudent = studentid;
end;                                                            
$$;


ALTER FUNCTION public.updatestudent(studentid character varying, newaddr character varying, newclass character varying) OWNER TO sms_admin;

--
-- Name: updatestudentaddress(character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updatestudentaddress(stno character varying, newaddr character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare 
appid varchar(100);
bioinfoid varchar(100);
begin
select vapplication into appid from teaching.student where vstudent = stNo;
select vbio_info into bioinfoid from application where vapplication = appid;
update bioinfo  set vaddress=newAddr where vbio_info = bioinfoid;
end;                                                            
$$;


ALTER FUNCTION public.updatestudentaddress(stno character varying, newaddr character varying) OWNER TO sms_admin;

--
-- Name: updatestudentclass(character varying, character varying); Type: FUNCTION; Schema: public; Owner: sms_admin
--

CREATE FUNCTION updatestudentclass(stno character varying, newclass character varying) RETURNS void
    LANGUAGE sql
    AS $$
update teaching.student  set vclass=newClass where vstudent=stNo;                                                            
$$;


ALTER FUNCTION public.updatestudentclass(stno character varying, newclass character varying) OWNER TO sms_admin;

SET search_path = admission, pg_catalog;

--
-- Name: login; Type: TABLE; Schema: admission; Owner: sms_admin
--

CREATE TABLE login (
    vusername character varying(255) NOT NULL,
    vpassword character varying(255) NOT NULL
);


ALTER TABLE login OWNER TO sms_admin;

SET search_path = facts, pg_catalog;

--
-- Name: countries; Type: TABLE; Schema: facts; Owner: sms_admin
--

CREATE TABLE countries (
    id bigint NOT NULL,
    vcode character varying(2) DEFAULT ''::character varying NOT NULL,
    vname character varying(100) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE countries OWNER TO sms_admin;

--
-- Name: countries_id_seq; Type: SEQUENCE; Schema: facts; Owner: sms_admin
--

CREATE SEQUENCE countries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE countries_id_seq OWNER TO sms_admin;

--
-- Name: countries_id_seq; Type: SEQUENCE OWNED BY; Schema: facts; Owner: sms_admin
--

ALTER SEQUENCE countries_id_seq OWNED BY countries.id;


--
-- Name: locals; Type: TABLE; Schema: facts; Owner: sms_admin
--

CREATE TABLE locals (
    id bigint NOT NULL,
    state_id integer NOT NULL,
    vlocal_name character varying(100) NOT NULL
);


ALTER TABLE locals OWNER TO sms_admin;

--
-- Name: locals_id_seq; Type: SEQUENCE; Schema: facts; Owner: sms_admin
--

CREATE SEQUENCE locals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE locals_id_seq OWNER TO sms_admin;

--
-- Name: locals_id_seq; Type: SEQUENCE OWNED BY; Schema: facts; Owner: sms_admin
--

ALTER SEQUENCE locals_id_seq OWNED BY locals.id;


--
-- Name: states; Type: TABLE; Schema: facts; Owner: sms_admin
--

CREATE TABLE states (
    id bigint NOT NULL,
    vname character varying(100) NOT NULL
);


ALTER TABLE states OWNER TO sms_admin;

--
-- Name: states_id_seq; Type: SEQUENCE; Schema: facts; Owner: sms_admin
--

CREATE SEQUENCE states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE states_id_seq OWNER TO sms_admin;

--
-- Name: states_id_seq; Type: SEQUENCE OWNED BY; Schema: facts; Owner: sms_admin
--

ALTER SEQUENCE states_id_seq OWNED BY states.id;


SET search_path = hr, pg_catalog;

--
-- Name: department; Type: TABLE; Schema: hr; Owner: sms_admin
--

CREATE TABLE department (
    vdepartment character varying(20) NOT NULL,
    vdescription character varying(100)
);


ALTER TABLE department OWNER TO sms_admin;

--
-- Name: login; Type: TABLE; Schema: hr; Owner: sms_admin
--

CREATE TABLE login (
    vusername character varying(255) NOT NULL,
    vpassword character varying(255)
);


ALTER TABLE login OWNER TO sms_admin;

--
-- Name: position; Type: TABLE; Schema: hr; Owner: sms_admin
--

CREATE TABLE "position" (
    vposition character varying(20) NOT NULL,
    vdescription character varying(100)
);


ALTER TABLE "position" OWNER TO sms_admin;

SET search_path = predef, pg_catalog;

--
-- Name: sys_class_duratn; Type: TABLE; Schema: predef; Owner: root
--

CREATE TABLE sys_class_duratn (
    bsid bigint NOT NULL,
    siduration smallint NOT NULL,
    vdesc character varying(30)
);


ALTER TABLE sys_class_duratn OWNER TO root;

--
-- Name: sys_class_duratn_bsid_seq; Type: SEQUENCE; Schema: predef; Owner: root
--

CREATE SEQUENCE sys_class_duratn_bsid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE sys_class_duratn_bsid_seq OWNER TO root;

--
-- Name: sys_class_duratn_bsid_seq; Type: SEQUENCE OWNED BY; Schema: predef; Owner: root
--

ALTER SEQUENCE sys_class_duratn_bsid_seq OWNED BY sys_class_duratn.bsid;


SET search_path = public, pg_catalog;

--
-- Name: application; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE application (
    vapplication character varying(100) NOT NULL,
    vbio_info character varying(100),
    vguardian_info character varying(100),
    veducation_info character varying(100),
    vmedical_info character varying(100),
    vsibling character varying(100),
    ddateofapplication timestamp with time zone NOT NULL
);


ALTER TABLE application OWNER TO sms_admin;

--
-- Name: bioinfo; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE bioinfo (
    vbio_info character varying(100) NOT NULL,
    vsurname character varying(100),
    vfirstname character varying(100),
    vothernames character varying(255),
    vgender character varying(10),
    ddob date,
    vaddress character varying(255),
    vpic character varying(255),
    CONSTRAINT bioinfo_vgender_check CHECK (((vgender)::text = ANY ((ARRAY['Male'::character varying, 'Female'::character varying])::text[])))
);


ALTER TABLE bioinfo OWNER TO sms_admin;

--
-- Name: candidate; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE candidate (
    vcandidate character varying(100) NOT NULL,
    vapplication character varying(100),
    vusername character varying(100) NOT NULL,
    vadmission_status character varying(20) DEFAULT 'NO ADMISSION'::character varying NOT NULL,
    vstudent_status character varying(100) DEFAULT 'NO'::character varying
);


ALTER TABLE candidate OWNER TO sms_admin;

--
-- Name: educationinfo; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE educationinfo (
    veducation_info character varying(100) NOT NULL,
    vtrainer_name character varying(255),
    vphone_num character varying(20),
    vaddress character varying(255)
);


ALTER TABLE educationinfo OWNER TO sms_admin;

--
-- Name: guardianinfo; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE guardianinfo (
    vguardian_info character varying(100) NOT NULL,
    vtitle character varying(100),
    vsurname character varying(100),
    vfirstname character varying(100),
    vothernames character varying(255),
    vaddress character varying(255),
    vpostcode character varying(10),
    vemail character varying(33),
    vdaytime_no character varying(20),
    vrelationship character varying(20),
    CONSTRAINT guardianinfo_vemail_check CHECK (((vemail)::text ~~ '%_@__%.__%'::text))
);


ALTER TABLE guardianinfo OWNER TO sms_admin;

--
-- Name: medicalinfo; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE medicalinfo (
    vmedical_info character varying(100) NOT NULL,
    vconditions character varying(255)
);


ALTER TABLE medicalinfo OWNER TO sms_admin;

--
-- Name: candidateinfo; Type: VIEW; Schema: public; Owner: sms_admin
--

CREATE VIEW candidateinfo AS
 SELECT a.vapplication,
    b.vfirstname AS c_fn,
    b.vsurname AS c_sn,
    b.vgender AS c_gender,
    b.ddob AS c_dob,
    b.vaddress AS c_addr,
    b.vpic AS c_pic,
    g.vtitle AS g_title,
    g.vsurname AS g_sn,
    g.vfirstname AS g_fn,
    g.vaddress AS g_addr,
    g.vpostcode AS g_pc,
    g.vemail AS g_email,
    g.vdaytime_no AS g_dtno,
    g.vrelationship AS g_rel,
    m.vconditions AS m_conds,
    e.vtrainer_name AS e_tn,
    e.vphone_num AS m_pnum,
    e.vaddress AS e_addr
   FROM application a,
    bioinfo b,
    guardianinfo g,
    educationinfo e,
    medicalinfo m
  WHERE (((a.vbio_info)::text = (b.vbio_info)::text) AND ((a.vguardian_info)::text = (g.vguardian_info)::text) AND ((a.veducation_info)::text = (e.veducation_info)::text) AND ((a.vmedical_info)::text = (m.vmedical_info)::text));


ALTER TABLE candidateinfo OWNER TO sms_admin;

--
-- Name: login; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE login (
    vusername character varying(100) NOT NULL,
    vpassword character varying(255) NOT NULL
);


ALTER TABLE login OWNER TO sms_admin;

--
-- Name: siblings; Type: TABLE; Schema: public; Owner: sms_admin
--

CREATE TABLE siblings (
    vsibling character varying(100) NOT NULL,
    vsurname character varying(100),
    vfirstname character varying(100),
    vothernames character varying(100)
);


ALTER TABLE siblings OWNER TO sms_admin;

--
-- Name: studentinfo_test; Type: VIEW; Schema: public; Owner: sms_admin
--

CREATE VIEW studentinfo_test AS
 SELECT b.vfirstname AS "Child Firstname",
    b.vsurname AS "Child Surname",
    g.vfirstname AS "Parent Firstname",
    g.vsurname AS "Parent Surname"
   FROM application a,
    bioinfo b,
    guardianinfo g
  WHERE (((a.vbio_info)::text = (b.vbio_info)::text) AND ((a.vguardian_info)::text = (g.vguardian_info)::text));


ALTER TABLE studentinfo_test OWNER TO sms_admin;

SET search_path = teaching, pg_catalog;

--
-- Name: student; Type: TABLE; Schema: teaching; Owner: sms_admin
--

CREATE TABLE student (
    vstudent character varying(100) NOT NULL,
    vapplication character varying(100) NOT NULL,
    vclass character varying(100) NOT NULL,
    dtstamp timestamp with time zone DEFAULT now()
);


ALTER TABLE student OWNER TO sms_admin;

SET search_path = public, pg_catalog;

--
-- Name: studentinfo_v; Type: VIEW; Schema: public; Owner: sms_admin
--

CREATE VIEW studentinfo_v AS
 SELECT c.vapplication,
    c.c_fn,
    c.c_sn,
    c.c_gender,
    c.c_dob,
    c.c_addr,
    c.c_pic,
    c.g_title,
    c.g_sn,
    c.g_fn,
    c.g_addr,
    c.g_pc,
    c.g_email,
    c.g_dtno,
    c.g_rel,
    c.m_conds,
    c.e_tn,
    c.m_pnum,
    c.e_addr,
    s.vstudent,
    s.vclass,
    s.dtstamp
   FROM candidateinfo c,
    teaching.student s
  WHERE ((c.vapplication)::text = (s.vapplication)::text);


ALTER TABLE studentinfo_v OWNER TO sms_admin;

SET search_path = teaching, pg_catalog;

--
-- Name: attreg; Type: TABLE; Schema: teaching; Owner: root
--

CREATE TABLE attreg (
    vattreg character varying(100) NOT NULL,
    vclass character varying(10)
);


ALTER TABLE attreg OWNER TO root;

--
-- Name: attreg_attregdetail; Type: TABLE; Schema: teaching; Owner: root
--

CREATE TABLE attreg_attregdetail (
    vattreg character varying(100) NOT NULL,
    ssattregdetail smallint NOT NULL
);


ALTER TABLE attreg_attregdetail OWNER TO root;

--
-- Name: attreg_attregdetail_ssattregdetail_seq; Type: SEQUENCE; Schema: teaching; Owner: root
--

CREATE SEQUENCE attreg_attregdetail_ssattregdetail_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE attreg_attregdetail_ssattregdetail_seq OWNER TO root;

--
-- Name: attreg_attregdetail_ssattregdetail_seq; Type: SEQUENCE OWNED BY; Schema: teaching; Owner: root
--

ALTER SEQUENCE attreg_attregdetail_ssattregdetail_seq OWNED BY attreg_attregdetail.ssattregdetail;


--
-- Name: attregdetail; Type: TABLE; Schema: teaching; Owner: root
--

CREATE TABLE attregdetail (
    ssattregdetail smallint NOT NULL,
    contactdate date NOT NULL,
    vfirstname character varying(30) NOT NULL,
    vlastname character varying(30) NOT NULL,
    battendance boolean DEFAULT false
);


ALTER TABLE attregdetail OWNER TO root;

--
-- Name: attregdetail_ssattregdetail_seq; Type: SEQUENCE; Schema: teaching; Owner: root
--

CREATE SEQUENCE attregdetail_ssattregdetail_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE attregdetail_ssattregdetail_seq OWNER TO root;

--
-- Name: attregdetail_ssattregdetail_seq; Type: SEQUENCE OWNED BY; Schema: teaching; Owner: root
--

ALTER SEQUENCE attregdetail_ssattregdetail_seq OWNED BY attregdetail.ssattregdetail;


--
-- Name: class; Type: TABLE; Schema: teaching; Owner: sms_admin
--

CREATE TABLE class (
    vclass character varying(100) NOT NULL,
    vclassteacher character varying(100),
    vclassname character varying(100),
    vclassroom character varying(100),
    bsid smallint
);


ALTER TABLE class OWNER TO sms_admin;

--
-- Name: classroom; Type: TABLE; Schema: teaching; Owner: sms_admin
--

CREATE TABLE classroom (
    vclassroom character varying(100) NOT NULL,
    vshortdescription character varying(255)
);


ALTER TABLE classroom OWNER TO sms_admin;

SET search_path = facts, pg_catalog;

--
-- Name: countries id; Type: DEFAULT; Schema: facts; Owner: sms_admin
--

ALTER TABLE ONLY countries ALTER COLUMN id SET DEFAULT nextval('countries_id_seq'::regclass);


--
-- Name: locals id; Type: DEFAULT; Schema: facts; Owner: sms_admin
--

ALTER TABLE ONLY locals ALTER COLUMN id SET DEFAULT nextval('locals_id_seq'::regclass);


--
-- Name: states id; Type: DEFAULT; Schema: facts; Owner: sms_admin
--

ALTER TABLE ONLY states ALTER COLUMN id SET DEFAULT nextval('states_id_seq'::regclass);


SET search_path = predef, pg_catalog;

--
-- Name: sys_class_duratn bsid; Type: DEFAULT; Schema: predef; Owner: root
--

ALTER TABLE ONLY sys_class_duratn ALTER COLUMN bsid SET DEFAULT nextval('sys_class_duratn_bsid_seq'::regclass);


SET search_path = teaching, pg_catalog;

--
-- Name: attreg_attregdetail ssattregdetail; Type: DEFAULT; Schema: teaching; Owner: root
--

ALTER TABLE ONLY attreg_attregdetail ALTER COLUMN ssattregdetail SET DEFAULT nextval('attreg_attregdetail_ssattregdetail_seq'::regclass);


--
-- Name: attregdetail ssattregdetail; Type: DEFAULT; Schema: teaching; Owner: root
--

ALTER TABLE ONLY attregdetail ALTER COLUMN ssattregdetail SET DEFAULT nextval('attregdetail_ssattregdetail_seq'::regclass);


SET search_path = admission, pg_catalog;

--
-- Data for Name: login; Type: TABLE DATA; Schema: admission; Owner: sms_admin
--

COPY login (vusername, vpassword) FROM stdin;
ao@test.com	$2y$10$aRBKr4putyiAP4FA3tc1r.pPcL6RrOzcB/SwnirbdCa52LnGIFyoO
\.


SET search_path = facts, pg_catalog;

--
-- Data for Name: countries; Type: TABLE DATA; Schema: facts; Owner: sms_admin
--

COPY countries (id, vcode, vname) FROM stdin;
1	AF	Afghanistan
2	AL	Albania
3	DZ	Algeria
4	DS	American Samoa
5	AD	Andorra
6	AO	Angola
7	AI	Anguilla
8	AQ	Antarctica
9	AG	Antigua and Barbuda
10	AR	Argentina
11	AM	Armenia
12	AW	Aruba
13	AU	Australia
14	AT	Austria
15	AZ	Azerbaijan
16	BS	Bahamas
17	BH	Bahrain
18	BD	Bangladesh
19	BB	Barbados
20	BY	Belarus
21	BE	Belgium
22	BZ	Belize
23	BJ	Benin
24	BM	Bermuda
25	BT	Bhutan
26	BO	Bolivia
27	BA	Bosnia and Herzegovina
28	BW	Botswana
29	BV	Bouvet Island
30	BR	Brazil
31	IO	British Indian Ocean Territory
32	BN	Brunei Darussalam
33	BG	Bulgaria
34	BF	Burkina Faso
35	BI	Burundi
36	KH	Cambodia
37	CM	Cameroon
38	CA	Canada
39	CV	Cape Verde
40	KY	Cayman Islands
41	CF	Central African Republic
42	TD	Chad
43	CL	Chile
44	CN	China
45	CX	Christmas Island
46	CC	Cocos (Keeling) Islands
47	CO	Colombia
48	KM	Comoros
49	CG	Congo
50	CK	Cook Islands
51	CR	Costa Rica
52	HR	Croatia (Hrvatska)
53	CU	Cuba
54	CY	Cyprus
55	CZ	Czech Republic
56	DK	Denmark
57	DJ	Djibouti
58	DM	Dominica
59	DO	Dominican Republic
60	TP	East Timor
61	EC	Ecuador
62	EG	Egypt
63	SV	El Salvador
64	GQ	Equatorial Guinea
65	ER	Eritrea
66	EE	Estonia
67	ET	Ethiopia
68	FK	Falkland Islands (Malvinas)
69	FO	Faroe Islands
70	FJ	Fiji
71	FI	Finland
72	FR	France
73	FX	France, Metropolitan
74	GF	French Guiana
75	PF	French Polynesia
76	TF	French Southern Territories
77	GA	Gabon
78	GM	Gambia
79	GE	Georgia
80	DE	Germany
81	GH	Ghana
82	GI	Gibraltar
83	GK	Guernsey
84	GR	Greece
85	GL	Greenland
86	GD	Grenada
87	GP	Guadeloupe
88	GU	Guam
89	GT	Guatemala
90	GN	Guinea
91	GW	Guinea-Bissau
92	GY	Guyana
93	HT	Haiti
94	HM	Heard and Mc Donald Islands
95	HN	Honduras
96	HK	Hong Kong
97	HU	Hungary
98	IS	Iceland
99	IN	India
100	IM	Isle of Man
101	ID	Indonesia
102	IR	Iran (Islamic Republic of)
103	IQ	Iraq
104	IE	Ireland
105	IL	Israel
106	IT	Italy
107	CI	Ivory Coast
108	JE	Jersey
109	JM	Jamaica
110	JP	Japan
111	JO	Jordan
112	KZ	Kazakhstan
113	KE	Kenya
114	KI	Kiribati
115	KP	Korea, Democratic People's Republic of
116	KR	Korea, Republic of
117	XK	Kosovo
118	KW	Kuwait
119	KG	Kyrgyzstan
120	LA	Lao People's Democratic Republic
121	LV	Latvia
122	LB	Lebanon
123	LS	Lesotho
124	LR	Liberia
125	LY	Libyan Arab Jamahiriya
126	LI	Liechtenstein
127	LT	Lithuania
128	LU	Luxembourg
129	MO	Macau
130	MK	Macedonia
131	MG	Madagascar
132	MW	Malawi
133	MY	Malaysia
134	MV	Maldives
135	ML	Mali
136	MT	Malta
137	MH	Marshall Islands
138	MQ	Martinique
139	MR	Mauritania
140	MU	Mauritius
141	TY	Mayotte
142	MX	Mexico
143	FM	Micronesia, Federated States of
144	MD	Moldova, Republic of
145	MC	Monaco
146	MN	Mongolia
147	ME	Montenegro
148	MS	Montserrat
149	MA	Morocco
150	MZ	Mozambique
151	MM	Myanmar
152	NA	Namibia
153	NR	Nauru
154	NP	Nepal
155	NL	Netherlands
156	AN	Netherlands Antilles
157	NC	New Caledonia
158	NZ	New Zealand
159	NI	Nicaragua
160	NE	Niger
161	NG	Nigeria
162	NU	Niue
163	NF	Norfolk Island
164	MP	Northern Mariana Islands
165	NO	Norway
166	OM	Oman
167	PK	Pakistan
168	PW	Palau
169	PS	Palestine
170	PA	Panama
171	PG	Papua New Guinea
172	PY	Paraguay
173	PE	Peru
174	PH	Philippines
175	PN	Pitcairn
176	PL	Poland
177	PT	Portugal
178	PR	Puerto Rico
179	QA	Qatar
180	RE	Reunion
181	RO	Romania
182	RU	Russian Federation
183	RW	Rwanda
184	KN	Saint Kitts and Nevis
185	LC	Saint Lucia
186	VC	Saint Vincent and the Grenadines
187	WS	Samoa
188	SM	San Marino
189	ST	Sao Tome and Principe
190	SA	Saudi Arabia
191	SN	Senegal
192	RS	Serbia
193	SC	Seychelles
194	SL	Sierra Leone
195	SG	Singapore
196	SK	Slovakia
197	SI	Slovenia
198	SB	Solomon Islands
199	SO	Somalia
200	ZA	South Africa
201	GS	South Georgia South Sandwich Islands
202	ES	Spain
203	LK	Sri Lanka
204	SH	St. Helena
205	PM	St. Pierre and Miquelon
206	SD	Sudan
207	SR	Suriname
208	SJ	Svalbard and Jan Mayen Islands
209	SZ	Swaziland
210	SE	Sweden
211	CH	Switzerland
212	SY	Syrian Arab Republic
213	TW	Taiwan
214	TJ	Tajikistan
215	TZ	Tanzania, United Republic of
216	TH	Thailand
217	TG	Togo
218	TK	Tokelau
219	TO	Tonga
220	TT	Trinidad and Tobago
221	TN	Tunisia
222	TR	Turkey
223	TM	Turkmenistan
224	TC	Turks and Caicos Islands
225	TV	Tuvalu
226	UG	Uganda
227	UA	Ukraine
228	AE	United Arab Emirates
229	GB	United Kingdom
230	US	United States
231	UM	United States minor outlying islands
232	UY	Uruguay
233	UZ	Uzbekistan
234	VU	Vanuatu
235	VA	Vatican City State
236	VE	Venezuela
237	VN	Vietnam
238	VG	Virgin Islands (British)
239	VI	Virgin Islands (U.S.)
240	WF	Wallis and Futuna Islands
241	EH	Western Sahara
242	YE	Yemen
243	ZR	Zaire
244	ZM	Zambia
245	ZW	Zimbabwe
\.


--
-- Name: countries_id_seq; Type: SEQUENCE SET; Schema: facts; Owner: sms_admin
--

SELECT pg_catalog.setval('countries_id_seq', 245, true);


--
-- Data for Name: locals; Type: TABLE DATA; Schema: facts; Owner: sms_admin
--

COPY locals (id, state_id, vlocal_name) FROM stdin;
1	1	Aba South
2	1	Arochukwu
3	1	Bende
4	1	Ikwuano
5	1	Isiala Ngwa North
6	1	Isiala Ngwa South
7	1	Isuikwuato
8	1	Obi Ngwa
9	1	Ohafia
10	1	Osisioma
11	1	Ugwunagbo
12	1	Ukwa East
13	1	Ukwa West
14	1	Umuahia North
15	1	Umuahia South
16	1	Umu Nneochi
17	2	Fufure
18	2	Ganye
19	2	Gayuk
20	2	Gombi
21	2	Grie
22	2	Hong
23	2	Jada
24	2	Lamurde
25	2	Madagali
26	2	Maiha
27	2	Mayo Belwa
28	2	Michika
29	2	Mubi North
30	2	Mubi South
31	2	Numan
32	2	Shelleng
33	2	Song
34	2	Toungo
35	2	Yola North
36	2	Yola South
37	3	Eastern Obolo
38	3	Eket
39	3	Esit Eket
40	3	Essien Udim
41	3	Etim Ekpo
42	3	Etinan
43	3	Ibeno
44	3	Ibesikpo Asutan
45	3	Ibiono-Ibom
46	3	Ika
47	3	Ikono
48	3	Ikot Abasi
49	3	Ikot Ekpene
50	3	Ini
51	3	Itu
52	3	Mbo
53	3	Mkpat-Enin
54	3	Nsit-Atai
55	3	Nsit-Ibom
56	3	Nsit-Ubium
57	3	Obot Akara
58	3	Okobo
59	3	Onna
60	3	Oron
61	3	Oruk Anam
62	3	Udung-Uko
63	3	Ukanafun
64	3	Uruan
65	3	Urue-Offong/Oruko
66	3	Uyo
67	4	Anambra East
68	4	Anambra West
69	4	Anaocha
70	4	Awka North
71	4	Awka South
72	4	Ayamelum
73	4	Dunukofia
74	4	Ekwusigo
75	4	Idemili North
76	4	Idemili South
77	4	Ihiala
78	4	Njikoka
79	4	Nnewi North
80	4	Nnewi South
81	4	Ogbaru
82	4	Onitsha North
83	4	Onitsha South
84	4	Orumba North
85	4	Orumba South
86	4	Oyi
87	5	Bauchi
88	5	Bogoro
89	5	Damban
90	5	Darazo
91	5	Dass
92	5	Gamawa
93	5	Ganjuwa
94	5	Giade
95	5	Itas/Gadau
96	5	Jama'are
97	5	Katagum
98	5	Kirfi
99	5	Misau
100	5	Ningi
101	5	Shira
102	5	Tafawa Balewa
103	5	Toro
104	5	Warji
105	5	Zaki
106	6	Ekeremor
107	6	Kolokuma/Opokuma
108	6	Nembe
109	6	Ogbia
110	6	Sagbama
111	6	Southern Ijaw
112	6	Yenagoa
113	7	Apa
114	7	Ado
115	7	Buruku
116	7	Gboko
117	7	Guma
118	7	Gwer East
119	7	Gwer West
120	7	Katsina-Ala
121	7	Konshisha
122	7	Kwande
123	7	Logo
124	7	Makurdi
125	7	Obi
126	7	Ogbadibo
127	7	Ohimini
128	7	Oju
129	7	Okpokwu
130	7	Oturkpo
131	7	Tarka
132	7	Ukum
133	7	Ushongo
134	7	Vandeikya
135	8	Askira/Uba
136	8	Bama
137	8	Bayo
138	8	Biu
139	8	Chibok
140	8	Damboa
141	8	Dikwa
142	8	Gubio
143	8	Guzamala
144	8	Gwoza
145	8	Hawul
146	8	Jere
147	8	Kaga
148	8	Kala/Balge
149	8	Konduga
150	8	Kukawa
151	8	Kwaya Kusar
152	8	Mafa
153	8	Magumeri
154	8	Maiduguri
155	8	Marte
156	8	Mobbar
157	8	Monguno
158	8	Ngala
159	8	Nganzai
160	8	Shani
161	9	Akamkpa
162	9	Akpabuyo
163	9	Bakassi
164	9	Bekwarra
165	9	Biase
166	9	Boki
167	9	Calabar Municipal
168	9	Calabar South
169	9	Etung
170	9	Ikom
171	9	Obanliku
172	9	Obubra
173	9	Obudu
174	9	Odukpani
175	9	Ogoja
176	9	Yakuur
177	9	Yala
178	10	Aniocha South
179	10	Bomadi
180	10	Burutu
181	10	Ethiope East
182	10	Ethiope West
183	10	Ika North East
184	10	Ika South
185	10	Isoko North
186	10	Isoko South
187	10	Ndokwa East
188	10	Ndokwa West
189	10	Okpe
190	10	Oshimili North
191	10	Oshimili South
192	10	Patani
193	10	Sapele
194	10	Udu
195	10	Ughelli North
196	10	Ughelli South
197	10	Ukwuani
198	10	Uvwie
199	10	Warri North
200	10	Warri South
201	10	Warri South West
202	11	Afikpo North
203	11	Afikpo South
204	11	Ebonyi
205	11	Ezza North
206	11	Ezza South
207	11	Ikwo
208	11	Ishielu
209	11	Ivo
210	11	Izzi
211	11	Ohaozara
212	11	Ohaukwu
213	11	Onicha
214	12	Egor
215	12	Esan Central
216	12	Esan North-East
217	12	Esan South-East
218	12	Esan West
219	12	Etsako Central
220	12	Etsako East
221	12	Etsako West
222	12	Igueben
223	12	Ikpoba Okha
224	12	Orhionmwon
225	12	Oredo
226	12	Ovia North-East
227	12	Ovia South-West
228	12	Owan East
229	12	Owan West
230	12	Uhunmwonde
231	13	Efon
232	13	Ekiti East
233	13	Ekiti South-West
234	13	Ekiti West
235	13	Emure
236	13	Gbonyin
237	13	Ido Osi
238	13	Ijero
239	13	Ikere
240	13	Ikole
241	13	Ilejemeje
242	13	Irepodun/Ifelodun
243	13	Ise/Orun
244	13	Moba
245	13	Oye
246	14	Awgu
247	14	Enugu East
248	14	Enugu North
249	14	Enugu South
250	14	Ezeagu
251	14	Igbo Etiti
252	14	Igbo Eze North
253	14	Igbo Eze South
254	14	Isi Uzo
255	14	Nkanu East
256	14	Nkanu West
257	14	Nsukka
258	14	Oji River
259	14	Udenu
260	14	Udi
261	14	Uzo Uwani
262	15	Bwari
263	15	Gwagwalada
264	15	Kuje
265	15	Kwali
266	15	Municipal Area Council
267	16	Balanga
268	16	Billiri
269	16	Dukku
270	16	Funakaye
271	16	Gombe
272	16	Kaltungo
273	16	Kwami
274	16	Nafada
275	16	Shongom
276	16	Yamaltu/Deba
277	17	Ahiazu Mbaise
278	17	Ehime Mbano
279	17	Ezinihitte
280	17	Ideato North
281	17	Ideato South
282	17	Ihitte/Uboma
283	17	Ikeduru
284	17	Isiala Mbano
285	17	Isu
286	17	Mbaitoli
287	17	Ngor Okpala
288	17	Njaba
289	17	Nkwerre
290	17	Nwangele
291	17	Obowo
292	17	Oguta
293	17	Ohaji/Egbema
294	17	Okigwe
295	17	Orlu
296	17	Orsu
297	17	Oru East
298	17	Oru West
299	17	Owerri Municipal
300	17	Owerri North
301	17	Owerri West
302	17	Unuimo
303	18	Babura
304	18	Biriniwa
305	18	Birnin Kudu
306	18	Buji
307	18	Dutse
308	18	Gagarawa
309	18	Garki
310	18	Gumel
311	18	Guri
312	18	Gwaram
313	18	Gwiwa
314	18	Hadejia
315	18	Jahun
316	18	Kafin Hausa
317	18	Kazaure
318	18	Kiri Kasama
319	18	Kiyawa
320	18	Kaugama
321	18	Maigatari
322	18	Malam Madori
323	18	Miga
324	18	Ringim
325	18	Roni
326	18	Sule Tankarkar
327	18	Taura
328	18	Yankwashi
329	19	Chikun
330	19	Giwa
331	19	Igabi
332	19	Ikara
333	19	Jaba
334	19	Jema'a
335	19	Kachia
336	19	Kaduna North
337	19	Kaduna South
338	19	Kagarko
339	19	Kajuru
340	19	Kaura
341	19	Kauru
342	19	Kubau
343	19	Kudan
344	19	Lere
345	19	Makarfi
346	19	Sabon Gari
347	19	Sanga
348	19	Soba
349	19	Zangon Kataf
350	19	Zaria
351	20	Albasu
352	20	Bagwai
353	20	Bebeji
354	20	Bichi
355	20	Bunkure
356	20	Dala
357	20	Dambatta
358	20	Dawakin Kudu
359	20	Dawakin Tofa
360	20	Doguwa
361	20	Fagge
362	20	Gabasawa
363	20	Garko
364	20	Garun Mallam
365	20	Gaya
366	20	Gezawa
367	20	Gwale
368	20	Gwarzo
369	20	Kabo
370	20	Kano Municipal
371	20	Karaye
372	20	Kibiya
373	20	Kiru
374	20	Kumbotso
375	20	Kunchi
376	20	Kura
377	20	Madobi
378	20	Makoda
379	20	Minjibir
380	20	Nasarawa
381	20	Rano
382	20	Rimin Gado
383	20	Rogo
384	20	Shanono
385	20	Sumaila
386	20	Takai
387	20	Tarauni
388	20	Tofa
389	20	Tsanyawa
390	20	Tudun Wada
391	20	Ungogo
392	20	Warawa
393	20	Wudil
394	21	Batagarawa
395	21	Batsari
396	21	Baure
397	21	Bindawa
398	21	Charanchi
399	21	Dandume
400	21	Danja
401	21	Dan Musa
402	21	Daura
403	21	Dutsi
404	21	Dutsin Ma
405	21	Faskari
406	21	Funtua
407	21	Ingawa
408	21	Jibia
409	21	Kafur
410	21	Kaita
411	21	Kankara
412	21	Kankia
413	21	Katsina
414	21	Kurfi
415	21	Kusada
416	21	Mai'Adua
417	21	Malumfashi
418	21	Mani
419	21	Mashi
420	21	Matazu
421	21	Musawa
422	21	Rimi
423	21	Sabuwa
424	21	Safana
425	21	Sandamu
426	21	Zango
427	22	Arewa Dandi
428	22	Argungu
429	22	Augie
430	22	Bagudo
431	22	Birnin Kebbi
432	22	Bunza
433	22	Dandi
434	22	Fakai
435	22	Gwandu
436	22	Jega
437	22	Kalgo
438	22	Koko/Besse
439	22	Maiyama
440	22	Ngaski
441	22	Sakaba
442	22	Shanga
443	22	Suru
444	22	Wasagu/Danko
445	22	Yauri
446	22	Zuru
447	23	Ajaokuta
448	23	Ankpa
449	23	Bassa
450	23	Dekina
451	23	Ibaji
452	23	Idah
453	23	Igalamela Odolu
454	23	Ijumu
455	23	Kabba/Bunu
456	23	Kogi
457	23	Lokoja
458	23	Mopa Muro
459	23	Ofu
460	23	Ogori/Magongo
461	23	Okehi
462	23	Okene
463	23	Olamaboro
464	23	Omala
465	23	Yagba East
466	23	Yagba West
467	24	Baruten
468	24	Edu
469	24	Ekiti
470	24	Ifelodun
471	24	Ilorin East
472	24	Ilorin South
473	24	Ilorin West
474	24	Irepodun
475	24	Isin
476	24	Kaiama
477	24	Moro
478	24	Offa
479	24	Oke Ero
480	24	Oyun
481	24	Pategi
482	25	Ajeromi-Ifelodun
483	25	Alimosho
484	25	Amuwo-Odofin
485	25	Apapa
486	25	Badagry
487	25	Epe
488	25	Eti Osa
489	25	Ibeju-Lekki
490	25	Ifako-Ijaiye
491	25	Ikeja
492	25	Ikorodu
493	25	Kosofe
494	25	Lagos Island
495	25	Lagos Mainland
496	25	Mushin
497	25	Ojo
498	25	Oshodi-Isolo
499	25	Shomolu
500	25	Surulere
501	26	Awe
502	26	Doma
503	26	Karu
504	26	Keana
505	26	Keffi
506	26	Kokona
507	26	Lafia
508	26	Nasarawa
509	26	Nasarawa Egon
510	26	Obi
511	26	Toto
512	26	Wamba
513	27	Agwara
514	27	Bida
515	27	Borgu
516	27	Bosso
517	27	Chanchaga
518	27	Edati
519	27	Gbako
520	27	Gurara
521	27	Katcha
522	27	Kontagora
523	27	Lapai
524	27	Lavun
525	27	Magama
526	27	Mariga
527	27	Mashegu
528	27	Mokwa
529	27	Moya
530	27	Paikoro
531	27	Rafi
532	27	Rijau
533	27	Shiroro
534	27	Suleja
535	27	Tafa
536	27	Wushishi
537	28	Abeokuta South
538	28	Ado-Odo/Ota
539	28	Egbado North
540	28	Egbado South
541	28	Ewekoro
542	28	Ifo
543	28	Ijebu East
544	28	Ijebu North
545	28	Ijebu North East
546	28	Ijebu Ode
547	28	Ikenne
548	28	Imeko Afon
549	28	Ipokia
550	28	Obafemi Owode
551	28	Odeda
552	28	Odogbolu
553	28	Ogun Waterside
554	28	Remo North
555	28	Shagamu
556	29	Akoko North-West
557	29	Akoko South-West
558	29	Akoko South-East
559	29	Akure North
560	29	Akure South
561	29	Ese Odo
562	29	Idanre
563	29	Ifedore
564	29	Ilaje
565	29	Ile Oluji/Okeigbo
566	29	Irele
567	29	Odigbo
568	29	Okitipupa
569	29	Ondo East
570	29	Ondo West
571	29	Ose
572	29	Owo
573	30	Atakunmosa West
574	30	Aiyedaade
575	30	Aiyedire
576	30	Boluwaduro
577	30	Boripe
578	30	Ede North
579	30	Ede South
580	30	Ife Central
581	30	Ife East
582	30	Ife North
583	30	Ife South
584	30	Egbedore
585	30	Ejigbo
586	30	Ifedayo
587	30	Ifelodun
588	30	Ila
589	30	Ilesa East
590	30	Ilesa West
591	30	Irepodun
592	30	Irewole
593	30	Isokan
594	30	Iwo
595	30	Obokun
596	30	Odo Otin
597	30	Ola Oluwa
598	30	Olorunda
599	30	Oriade
600	30	Orolu
601	30	Osogbo
602	31	Akinyele
603	31	Atiba
604	31	Atisbo
605	31	Egbeda
606	31	Ibadan North
607	31	Ibadan North-East
608	31	Ibadan North-West
609	31	Ibadan South-East
610	31	Ibadan South-West
611	31	Ibarapa Central
612	31	Ibarapa East
613	31	Ibarapa North
614	31	Ido
615	31	Irepo
616	31	Iseyin
617	31	Itesiwaju
618	31	Iwajowa
619	31	Kajola
620	31	Lagelu
621	31	Ogbomosho North
622	31	Ogbomosho South
623	31	Ogo Oluwa
624	31	Olorunsogo
625	31	Oluyole
626	31	Ona Ara
627	31	Orelope
628	31	Ori Ire
629	31	Oyo
630	31	Oyo East
631	31	Saki East
632	31	Saki West
633	31	Surulere
634	32	Barkin Ladi
635	32	Bassa
636	32	Jos East
637	32	Jos North
638	32	Jos South
639	32	Kanam
640	32	Kanke
641	32	Langtang South
642	32	Langtang North
643	32	Mangu
644	32	Mikang
645	32	Pankshin
646	32	Qua'an Pan
647	32	Riyom
648	32	Shendam
649	32	Wase
650	33	Ahoada East
651	33	Ahoada West
652	33	Akuku-Toru
653	33	Andoni
654	33	Asari-Toru
655	33	Bonny
656	33	Degema
657	33	Eleme
658	33	Emuoha
659	33	Etche
660	33	Gokana
661	33	Ikwerre
662	33	Khana
663	33	Obio/Akpor
664	33	Ogba/Egbema/Ndoni
665	33	Ogu/Bolo
666	33	Okrika
667	33	Omuma
668	33	Opobo/Nkoro
669	33	Oyigbo
670	33	Port Harcourt
671	33	Tai
672	34	Bodinga
673	34	Dange Shuni
674	34	Gada
675	34	Goronyo
676	34	Gudu
677	34	Gwadabawa
678	34	Illela
679	34	Isa
680	34	Kebbe
681	34	Kware
682	34	Rabah
683	34	Sabon Birni
684	34	Shagari
685	34	Silame
686	34	Sokoto North
687	34	Sokoto South
688	34	Tambuwal
689	34	Tangaza
690	34	Tureta
691	34	Wamako
692	34	Wurno
693	34	Yabo
694	35	Bali
695	35	Donga
696	35	Gashaka
697	35	Gassol
698	35	Ibi
699	35	Jalingo
700	35	Karim Lamido
701	35	Kumi
702	35	Lau
703	35	Sardauna
704	35	Takum
705	35	Ussa
706	35	Wukari
707	35	Yorro
708	35	Zing
709	36	Bursari
710	36	Damaturu
711	36	Fika
712	36	Fune
713	36	Geidam
714	36	Gujba
715	36	Gulani
716	36	Jakusko
717	36	Karasuwa
718	36	Machina
719	36	Nangere
720	36	Nguru
721	36	Potiskum
722	36	Tarmuwa
723	36	Yunusari
724	36	Yusufari
725	37	Bakura
726	37	Birnin Magaji/Kiyaw
727	37	Bukkuyum
728	37	Bungudu
729	37	Gummi
730	37	Gusau
731	37	Kaura Namoda
732	37	Maradun
733	37	Maru
734	37	Shinkafi
735	37	Talata Mafara
736	37	Chafe
737	37	Zurmi
\.


--
-- Name: locals_id_seq; Type: SEQUENCE SET; Schema: facts; Owner: sms_admin
--

SELECT pg_catalog.setval('locals_id_seq', 1, false);


--
-- Data for Name: states; Type: TABLE DATA; Schema: facts; Owner: sms_admin
--

COPY states (id, vname) FROM stdin;
1	Abia State
2	Adamawa State
3	Akwa Ibom State
4	Anambra State
5	Bauchi State
6	Bayelsa State
7	Benue State
8	Borno State
9	Cross River State
10	Delta State
11	Ebonyi State
12	Edo State
13	Ekiti State
14	Enugu State
15	FCT
16	Gombe State
17	Imo State
18	Jigawa State
19	Kaduna State
20	Kano State
21	Katsina State
22	Kebbi State
23	Kogi State
24	Kwara State
25	Lagos State
26	Nasarawa State
27	Niger State
28	Ogun State
29	Ondo State
30	Osun State
31	Oyo State
32	Plateau State
33	Rivers State
34	Sokoto State
35	Taraba State
36	Yobe State
37	Zamfara State
\.


--
-- Name: states_id_seq; Type: SEQUENCE SET; Schema: facts; Owner: sms_admin
--

SELECT pg_catalog.setval('states_id_seq', 1, false);


SET search_path = hr, pg_catalog;

--
-- Data for Name: department; Type: TABLE DATA; Schema: hr; Owner: sms_admin
--

COPY department (vdepartment, vdescription) FROM stdin;
91e06	Management
\.


--
-- Data for Name: employee; Type: TABLE DATA; Schema: hr; Owner: sms_admin
--

COPY employee (vemployee, vfirstname, vothername, vlastname, vaddress_one, vaddress_two, vstateoforigin, vcountry, ddateofemp, vdepartment, vposition, vpicture) FROM stdin;
E201825BA3	JOHN	O	OKAFOR	PLT 31/32 NEW LAGOS ROAD BENIN	EDO / DELTA	Edo	Nigeria	2015-01-18	91e06	a7f5e	../../uploads/emp1.jpg
E2018881C9	GRACE	JOHN	LURTHANZA	23 lekki road	Lagos	Lagos	Nigeria	2016-12-19	91e06	a7f5e	../../uploads/emp2.jpg
E201803F81	OMORUYI	JOHN	OSARUMWENSE	11 Adesuwa Rd 	Benin	Edo	Nigeria	2013-04-25	91e06	3C52E	../../uploads/male-placeholder.jpg
E2018D0E09	JOSEPH	PEACE	CELESTINE	No 12 Ike Street	Benin	Edo	Nigeria	2016-04-25	91e06	3C52E	../../uploads/joseph_celestine.jpg
\.


--
-- Data for Name: login; Type: TABLE DATA; Schema: hr; Owner: sms_admin
--

COPY login (vusername, vpassword) FROM stdin;
hr@test.com	$2y$10$R7D1vy1F.ah5XxuDjvYqzeHi6oVdzZnT5HxiDgEEHkmSo3qJJiB2e
\.


--
-- Data for Name: position; Type: TABLE DATA; Schema: hr; Owner: sms_admin
--

COPY "position" (vposition, vdescription) FROM stdin;
a7f5e	Head of Teacher
3C52E	Teacher
\.


SET search_path = predef, pg_catalog;

--
-- Data for Name: sys_class_duratn; Type: TABLE DATA; Schema: predef; Owner: root
--

COPY sys_class_duratn (bsid, siduration, vdesc) FROM stdin;
2	60	Term - 60 days
3	180	Session - 180 days
\.


--
-- Name: sys_class_duratn_bsid_seq; Type: SEQUENCE SET; Schema: predef; Owner: root
--

SELECT pg_catalog.setval('sys_class_duratn_bsid_seq', 3, true);


SET search_path = public, pg_catalog;

--
-- Data for Name: application; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY application (vapplication, vbio_info, vguardian_info, veducation_info, vmedical_info, vsibling, ddateofapplication) FROM stdin;
A2017e6e5bf7	2550d	\N	\N	\N	\N	2017-12-20 15:18:00+01
A201775f33899033b370d562db114e	97e86	9a4c4	\N	\N	\N	2017-12-18 10:05:39+01
A2018b35f0ae	36ee4	6c246	ff802	c87e0	\N	2018-01-15 17:48:05+01
A2018f639adc	a25ac	d7731	de59d	09b17	\N	2018-01-15 18:16:21+01
A2018f378e3a	95c77	46c61	43947	497e7	\N	2018-01-15 18:16:59+01
A20181473a18	ce59b	84253	b764e	1c104	\N	2018-01-21 09:17:55+01
A2018fa6a1db	b5240	7d388	5865a	c6e10	\N	2018-01-23 11:43:12+01
A2018262ce08	95426	edbd3	79a71	152da	\N	2018-01-23 11:57:06+01
A2018a07660d	60593	45b73	975e6	ce14f	\N	2018-01-23 12:09:30+01
A2018e44762b	30cf4	b579e	22590	51560	\N	2018-01-23 13:24:14+01
A201883DBD71	11df1	5d8c7	042e6	7b4a9	\N	2018-02-05 17:06:00+01
A2018CC92634	46309	161d5	9c98f	73b5f	\N	2018-02-22 16:30:30+01
A20185D7675C	a24bf	c9415	7eac8	18709	\N	2018-03-16 19:06:02+01
\.


--
-- Data for Name: bioinfo; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY bioinfo (vbio_info, vsurname, vfirstname, vothernames, vgender, ddob, vaddress, vpic) FROM stdin;
97e86	TestSurname	TestFirstname	t	Female	1980-04-05	Test Address	\N
2550d	TestSurname	TestFirstname	TestOthername	Female	1980-04-05	Test Address	\N
95036	Test	Test	Test	Male	2016-12-03	Test	\N
74d8f	Test	Test	Test	Male	2016-12-03	Test	\N
075e0	Test	Test	Test	Male	2016-12-03	Test	\N
a58fa	Test	Test	Test	Male	2016-12-03	Test	\N
a9dbc	Test	Test	Test	Male	2016-12-03	Test	\N
25693	Test	Test	Test	Male	2016-12-03	Test	\N
cfef2	Test	Test	Test	Male	2016-12-03	Test	\N
5f6af	Test	Test	Test	Male	2016-12-03	Test	\N
8d478	Test	Test	Test	Male	2016-12-03	Test	\N
9b98d	Test	Test	Test	Male	2016-12-03	Test	\N
d684e	Test	Test	Test	Male	2016-12-03	Test	\N
70c45	Test	Test	Test	Male	2016-12-03	Test	\N
36ee4	Test	Test	Test	Male	2016-12-03	Test	\N
a25ac	Test	Test	Test	Male	2016-12-03	Test	\N
95c77	Test	Test	Test	Male	2016-12-03	Test	\N
e015c	uu	uu	uu	Female	2016-12-03	ii	\N
b5240	Parent1	Parent2		Female	2016-12-03	Test	../../uploads/ward1.jpg
95426	Parent2	Parent2		Female	2016-12-03	Test	../../uploads/ward2.jpg
dd181				Female	2016-12-03		../../uploads/ward3.jpg
30cf4	Parent 5	Ward5	Ward	Male	2016-12-03	Parent 5 Address	../../uploads/ward5.jpg
11df1	Odeyemi	John		Female	2016-12-03	Grandeur street off ikeja lagos	../../uploads/jo.jpg
ce59b	Odeyemi	John	None	Male	2012-12-03	Plt 2/3 Chief Samuel Oniyan Streets	../../uploads/micheal_odeyemi.jpg
60593	Ward4	Ward4		Female	2016-12-03	Plt 2/3 Chief Samuel oniyan Streets	../../uploads/ward4.jpg
46309	Oats	jiji	O	Male	2011-12-15	Jiji Street off Ijegun Road Benin City	../../uploads/jiji.jpg
a24bf	Parker	Racheal		Male	1994-02-15	Jiji Street off Ijegun Road Benin City	../../uploads/racheal.jpg
\.


--
-- Data for Name: candidate; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY candidate (vcandidate, vapplication, vusername, vadmission_status, vstudent_status) FROM stdin;
C2017e02974fc1206e6ee4569804b0ea	A201775f33899033b370d562db114e	test@test.com	NO ADMISSION	NO
C2017ce95b	A2017e6e5bf7	admin@test.com	NO ADMISSION	NO
C201883c41	A20181473a18	john@test.com	GRANTED	YES
C2018ea432	A2018a07660d	p4@test.com	GRANTED	YES
C2018d8a23	A2018e44762b	p5@test.com	GRANTED	YES
C201818523	A2018262ce08	p2@test.com	GRANTED	YES
C20187B985	A201883DBD71	imole@gmail.com	GRANTED	YES
C2018e0293	A2018f378e3a	grace	GRANTED	YES
C2018893d5	A2018fa6a1db	p1@test.com	GRANTED	YES
C20182ADDF	A2018CC92634	jiji@test.com	NO ADMISSION	NO
C2018219A1	A20185D7675C	s1@test.com	GRANTED	NO
\.


--
-- Data for Name: educationinfo; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY educationinfo (veducation_info, vtrainer_name, vphone_num, vaddress) FROM stdin;
c7f2d	Vinyl	09898766666	Test
b211b	Vinyl	09898766666	Test
abfc8	Vinyl	09898766666	Test
fd531	Vinyl	09898766666	Test
ff802	Vinyl	09898766666	Test
de59d	Vinyl	09898766666	Test
43947	Vinyl	09898766666	Test
8b4c2	ii	88989898989	ii
b764e	Vinily	09898766666	Test
5865a	Vinyl	88989898989	Test
79a71	Vinyl	88989898989	Test
8e6c7	Vinyl	88989898989	Test
975e6	Vinyl	88989898989	Test
22590	Vinyl	09898766666	Test
042e6	Vinyl	88989898989	Test
9c98f	Tanyoun International School	09876543245	Tanyoun International School Lane Victoria Island
7eac8	Tanyoun International School	09876543245	Tanyoun International School Lane Victoria Island
\.


--
-- Data for Name: guardianinfo; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY guardianinfo (vguardian_info, vtitle, vsurname, vfirstname, vothernames, vaddress, vpostcode, vemail, vdaytime_no, vrelationship) FROM stdin;
52eff	Mr	TestSurname	TestFirstname		Test Address	123123	test@test.com	08067543452	Parent
3abfe	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
9aedd	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
a9bf6	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
1633e	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
9a4c4	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
70949	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
5a218	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
5ed26	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
98e38	Mr	TestSurname	TestFirstname	TestOthername	Test Address	123123	test@test.com	08067543452	Uncle
ac42b	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
572ed	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
30c21	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
4ca51	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
2bd1b	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
71584	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
4a7f2	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
60197	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
2a3ff	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
b32dd	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
aa38a	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
a39aa	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
6c246	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
d7731	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
46c61	Mr	Test	Test	Test	Test	Test	test@gmail.com	08055509249	Parent
7d388	Miss	Parent1	Parent2		Test	00234	p1@test.com	08055509249	Parent
edbd3	Miss	Parent2	Parent2		Test	00234	p1@test.com	08055509249	Parent
33af1	Miss	Parent3	Parent3		Test	00234	p3@test.com	08055509249	Parent
b579e	Mr	Parent 5	Parent 5		Test	00123	p5@test.com	08055509249	Parent
5d8c7	Mrs	Odeyemi	Angela		Test	00234	jo@test.com	0987654322	Parent
84253	Mr	Odeyemi	John	None	PLT 2/3 Chief Samuel Oniyan Streets	00234	odeyemi@test.com	08098765436	Parent
45b73	Miss	Parent4	Parent4		Test	00234	p3@test.com	08055509249	Parent
161d5	Mrs	Jiji	Mary	P	Tanyoun International School Lane Victoria Island	00234	jiji@test.com	08098675463	Parent
c9415	Mr	Parker	Jimmy		Tanyoun International School Lane Victoria Island	00234	jiji@test.com	08098675463	Parent
\.


--
-- Data for Name: login; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY login (vusername, vpassword) FROM stdin;
test@test.com	$2y$10$79EQ2rklaiWdXG.1j1sHruIO4JxQWWda4v1SLGhnqbSQyigINcJTi
admin@test.com	$2y$10$lNN8V908FchJVCUlcL6xD.WoSdV8aLrLz7hg8YvVmmB20IpFmLhjK
grace	$2y$10$JWuTANJ3E67xjWFl790JieXd.hH21619EQFRzVM.nhyi03hYZkFYK
john@test.com	$2y$10$/bLoK22LynX19UBHJxOPFuGAQNo5L9MNk8xmrHUuREqTgys6jx/ta
p1@test.com	$2y$10$iwba6CryUGJjJhmGYpU0n.gj1NdzSXbPxU8YRFjcY45o9edXB9dXS
p2@test.com	$2y$10$ta6CMsdOwRw.AumGzptgWu5Qpmuw.i.BzAQNyVbhdHvejl1.jhjFu
p3@test.com	$2y$10$1eOfdjywhIC0HoU1I3On4O.sIJ01uAnvRIs9Gp5iK74YWfM3TCTve
p4@test.com	$2y$10$675on1qkX0QmqohCOAf29uw969o9s4M8WUsXW5NOyu.rRZfLrrlgq
p5@test.com	$2y$10$E3c2BYAeI58ilBsxY8PN1OWnO4zr0s4bqClZgiWIFnVcKm1Y36epe
imole@gmail.com	$2y$10$Qp1uKqHixUXbWjsOuKocyu3LwYyh6raCKufThRs7KECdJtNidSBUS
jiji@test.com	$2y$10$VLIFdiVVpaWLzFABgoewMeRYORK8Ms6eYCc2KXL6/ARdA0vXvI/d6
s1@test.com	$2y$10$2gVltFsaZLWl4jl46b6Xm.E.nej79AR7bxDvnA3w0bkag/zG26/Wm
\.


--
-- Data for Name: medicalinfo; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY medicalinfo (vmedical_info, vconditions) FROM stdin;
e8556	None 
b1216	None 
57e40	None 
c87e0	None 
09b17	None 
497e7	None 
54e53	none 
1c104	NONE 
c6e10	NONE
152da	NONE
815fb	NONE
ce14f	NONE
51560	NONE 
7b4a9	 NONE
73b5f	 None
18709	None 
\.


--
-- Data for Name: siblings; Type: TABLE DATA; Schema: public; Owner: sms_admin
--

COPY siblings (vsibling, vsurname, vfirstname, vothernames) FROM stdin;
\.


SET search_path = teaching, pg_catalog;

--
-- Data for Name: attreg; Type: TABLE DATA; Schema: teaching; Owner: root
--

COPY attreg (vattreg, vclass) FROM stdin;
\.


--
-- Data for Name: attreg_attregdetail; Type: TABLE DATA; Schema: teaching; Owner: root
--

COPY attreg_attregdetail (vattreg, ssattregdetail) FROM stdin;
\.


--
-- Name: attreg_attregdetail_ssattregdetail_seq; Type: SEQUENCE SET; Schema: teaching; Owner: root
--

SELECT pg_catalog.setval('attreg_attregdetail_ssattregdetail_seq', 1, false);


--
-- Data for Name: attregdetail; Type: TABLE DATA; Schema: teaching; Owner: root
--

COPY attregdetail (ssattregdetail, contactdate, vfirstname, vlastname, battendance) FROM stdin;
\.


--
-- Name: attregdetail_ssattregdetail_seq; Type: SEQUENCE SET; Schema: teaching; Owner: root
--

SELECT pg_catalog.setval('attregdetail_ssattregdetail_seq', 1, false);


--
-- Data for Name: class; Type: TABLE DATA; Schema: teaching; Owner: sms_admin
--

COPY class (vclass, vclassteacher, vclassname, vclassroom, bsid) FROM stdin;
P1A	E2018D0E09	PRIMARY ONE A	P1A	2
P1B	E201803F81	PRIMARY ONE B	P1B	3
\.


--
-- Data for Name: classroom; Type: TABLE DATA; Schema: teaching; Owner: sms_admin
--

COPY classroom (vclassroom, vshortdescription) FROM stdin;
P1A	PRIMARY 1 CLASSROOM A
P1B	PRIMARY 1 CLASSROOM B
P1C	PRIMARY 1 CLASSROOM C
\.


--
-- Data for Name: student; Type: TABLE DATA; Schema: teaching; Owner: sms_admin
--

COPY student (vstudent, vapplication, vclass, dtstamp) FROM stdin;
R20182490560	A2018e44762b	P1A	2018-01-26 13:57:28.2071+01
R201876DF358	A2018262ce08	P1A	2018-01-26 13:57:40.057629+01
R2018C07503B	A201883DBD71	P1A	2018-02-05 18:06:54.395271+01
R201872D36F6	A2018f378e3a	P1A	2018-02-20 16:53:59.122344+01
R201882E9998	A2018fa6a1db	P1B	2018-02-20 16:54:12.391821+01
R2018ECCD958	A20181473a18	P1A	2018-01-25 17:12:03.940504+01
R201895BBF8D	A2018a07660d	P1B	2018-01-25 18:49:45.014922+01
\.


SET search_path = admission, pg_catalog;

--
-- Name: login login_pkey; Type: CONSTRAINT; Schema: admission; Owner: sms_admin
--

ALTER TABLE ONLY login
    ADD CONSTRAINT login_pkey PRIMARY KEY (vusername);


SET search_path = facts, pg_catalog;

--
-- Name: countries countries_pkey; Type: CONSTRAINT; Schema: facts; Owner: sms_admin
--

ALTER TABLE ONLY countries
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: locals locals_pkey; Type: CONSTRAINT; Schema: facts; Owner: sms_admin
--

ALTER TABLE ONLY locals
    ADD CONSTRAINT locals_pkey PRIMARY KEY (id);


--
-- Name: states states_pkey; Type: CONSTRAINT; Schema: facts; Owner: sms_admin
--

ALTER TABLE ONLY states
    ADD CONSTRAINT states_pkey PRIMARY KEY (id);


SET search_path = hr, pg_catalog;

--
-- Name: department department_pkey; Type: CONSTRAINT; Schema: hr; Owner: sms_admin
--

ALTER TABLE ONLY department
    ADD CONSTRAINT department_pkey PRIMARY KEY (vdepartment);


--
-- Name: employee employee_pkey; Type: CONSTRAINT; Schema: hr; Owner: sms_admin
--

ALTER TABLE ONLY employee
    ADD CONSTRAINT employee_pkey PRIMARY KEY (vemployee);


--
-- Name: login login_pkey; Type: CONSTRAINT; Schema: hr; Owner: sms_admin
--

ALTER TABLE ONLY login
    ADD CONSTRAINT login_pkey PRIMARY KEY (vusername);


--
-- Name: position position_pkey; Type: CONSTRAINT; Schema: hr; Owner: sms_admin
--

ALTER TABLE ONLY "position"
    ADD CONSTRAINT position_pkey PRIMARY KEY (vposition);


--
-- Name: position uqdesc; Type: CONSTRAINT; Schema: hr; Owner: sms_admin
--

ALTER TABLE ONLY "position"
    ADD CONSTRAINT uqdesc UNIQUE (vdescription);


SET search_path = predef, pg_catalog;

--
-- Name: sys_class_duratn sys_class_duratn_pkey; Type: CONSTRAINT; Schema: predef; Owner: root
--

ALTER TABLE ONLY sys_class_duratn
    ADD CONSTRAINT sys_class_duratn_pkey PRIMARY KEY (bsid);


SET search_path = public, pg_catalog;

--
-- Name: application application_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY application
    ADD CONSTRAINT application_pkey PRIMARY KEY (vapplication);


--
-- Name: bioinfo bioinfo_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY bioinfo
    ADD CONSTRAINT bioinfo_pkey PRIMARY KEY (vbio_info);


--
-- Name: candidate candidate_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY candidate
    ADD CONSTRAINT candidate_pkey PRIMARY KEY (vcandidate);


--
-- Name: educationinfo educationinfo_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY educationinfo
    ADD CONSTRAINT educationinfo_pkey PRIMARY KEY (veducation_info);


--
-- Name: guardianinfo guardianinfo_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY guardianinfo
    ADD CONSTRAINT guardianinfo_pkey PRIMARY KEY (vguardian_info);


--
-- Name: login login_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY login
    ADD CONSTRAINT login_pkey PRIMARY KEY (vusername);


--
-- Name: medicalinfo medicalinfo_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY medicalinfo
    ADD CONSTRAINT medicalinfo_pkey PRIMARY KEY (vmedical_info);


--
-- Name: siblings siblings_pkey; Type: CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY siblings
    ADD CONSTRAINT siblings_pkey PRIMARY KEY (vsibling);


SET search_path = teaching, pg_catalog;

--
-- Name: attreg attreg_pkey; Type: CONSTRAINT; Schema: teaching; Owner: root
--

ALTER TABLE ONLY attreg
    ADD CONSTRAINT attreg_pkey PRIMARY KEY (vattreg);


--
-- Name: attregdetail attregdetail_pkey; Type: CONSTRAINT; Schema: teaching; Owner: root
--

ALTER TABLE ONLY attregdetail
    ADD CONSTRAINT attregdetail_pkey PRIMARY KEY (ssattregdetail);


--
-- Name: class class_pkey; Type: CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY class
    ADD CONSTRAINT class_pkey PRIMARY KEY (vclass);


--
-- Name: classroom classroom_pkey; Type: CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY classroom
    ADD CONSTRAINT classroom_pkey PRIMARY KEY (vclassroom);


--
-- Name: student student_pkey; Type: CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY student
    ADD CONSTRAINT student_pkey PRIMARY KEY (vstudent);


--
-- Name: student uqapplication; Type: CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY student
    ADD CONSTRAINT uqapplication UNIQUE (vapplication);


SET search_path = hr, pg_catalog;

--
-- Name: employee fkemp_dept; Type: FK CONSTRAINT; Schema: hr; Owner: sms_admin
--

ALTER TABLE ONLY employee
    ADD CONSTRAINT fkemp_dept FOREIGN KEY (vdepartment) REFERENCES department(vdepartment);


--
-- Name: employee fkemp_pos; Type: FK CONSTRAINT; Schema: hr; Owner: sms_admin
--

ALTER TABLE ONLY employee
    ADD CONSTRAINT fkemp_pos FOREIGN KEY (vposition) REFERENCES "position"(vposition);


SET search_path = public, pg_catalog;

--
-- Name: application application_vbio_info_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY application
    ADD CONSTRAINT application_vbio_info_fkey FOREIGN KEY (vbio_info) REFERENCES bioinfo(vbio_info);


--
-- Name: application application_veducation_info_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY application
    ADD CONSTRAINT application_veducation_info_fkey FOREIGN KEY (veducation_info) REFERENCES educationinfo(veducation_info);


--
-- Name: application application_vguardian_info_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY application
    ADD CONSTRAINT application_vguardian_info_fkey FOREIGN KEY (vguardian_info) REFERENCES guardianinfo(vguardian_info);


--
-- Name: application application_vmedical_info_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY application
    ADD CONSTRAINT application_vmedical_info_fkey FOREIGN KEY (vmedical_info) REFERENCES medicalinfo(vmedical_info);


--
-- Name: candidate candidate_vapplication_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY candidate
    ADD CONSTRAINT candidate_vapplication_fkey FOREIGN KEY (vapplication) REFERENCES application(vapplication);


--
-- Name: candidate candidate_vusername_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sms_admin
--

ALTER TABLE ONLY candidate
    ADD CONSTRAINT candidate_vusername_fkey FOREIGN KEY (vusername) REFERENCES login(vusername);


SET search_path = teaching, pg_catalog;

--
-- Name: student fkapp; Type: FK CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY student
    ADD CONSTRAINT fkapp FOREIGN KEY (vapplication) REFERENCES public.application(vapplication);


--
-- Name: attreg_attregdetail fkattreg; Type: FK CONSTRAINT; Schema: teaching; Owner: root
--

ALTER TABLE ONLY attreg_attregdetail
    ADD CONSTRAINT fkattreg FOREIGN KEY (vattreg) REFERENCES attreg(vattreg);


--
-- Name: attreg_attregdetail fkattregdetail; Type: FK CONSTRAINT; Schema: teaching; Owner: root
--

ALTER TABLE ONLY attreg_attregdetail
    ADD CONSTRAINT fkattregdetail FOREIGN KEY (ssattregdetail) REFERENCES attregdetail(ssattregdetail);


--
-- Name: attreg fkclass; Type: FK CONSTRAINT; Schema: teaching; Owner: root
--

ALTER TABLE ONLY attreg
    ADD CONSTRAINT fkclass FOREIGN KEY (vclass) REFERENCES class(vclass);


--
-- Name: class fkclass_duratn; Type: FK CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY class
    ADD CONSTRAINT fkclass_duratn FOREIGN KEY (bsid) REFERENCES predef.sys_class_duratn(bsid);


--
-- Name: class fkemployee; Type: FK CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY class
    ADD CONSTRAINT fkemployee FOREIGN KEY (vclassteacher) REFERENCES hr.employee(vemployee);


--
-- Name: student fkvclass; Type: FK CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY student
    ADD CONSTRAINT fkvclass FOREIGN KEY (vclass) REFERENCES class(vclass);


--
-- Name: class fkvclassrm; Type: FK CONSTRAINT; Schema: teaching; Owner: sms_admin
--

ALTER TABLE ONLY class
    ADD CONSTRAINT fkvclassrm FOREIGN KEY (vclassroom) REFERENCES classroom(vclassroom);


--
-- Name: admission; Type: ACL; Schema: -; Owner: sms_admin
--

GRANT USAGE ON SCHEMA admission TO ao;


--
-- Name: facts; Type: ACL; Schema: -; Owner: sms_admin
--

GRANT USAGE ON SCHEMA facts TO mgt;
GRANT USAGE ON SCHEMA facts TO applicant;


--
-- Name: hr; Type: ACL; Schema: -; Owner: sms_admin
--

GRANT USAGE ON SCHEMA hr TO mgt;
GRANT USAGE ON SCHEMA hr TO hr;


--
-- Name: predef; Type: ACL; Schema: -; Owner: root
--

GRANT USAGE ON SCHEMA predef TO mgt;


--
-- Name: teaching; Type: ACL; Schema: -; Owner: sms_admin
--

GRANT USAGE ON SCHEMA teaching TO mgt;
GRANT USAGE ON SCHEMA teaching TO ao;


SET search_path = hr, pg_catalog;

--
-- Name: employee; Type: ACL; Schema: hr; Owner: sms_admin
--

GRANT SELECT,INSERT,UPDATE ON TABLE employee TO applicant;
GRANT ALL ON TABLE employee TO hr;


SET search_path = admission, pg_catalog;

--
-- Name: login; Type: ACL; Schema: admission; Owner: sms_admin
--

GRANT SELECT ON TABLE login TO ao;
GRANT SELECT ON TABLE login TO applicant;


SET search_path = facts, pg_catalog;

--
-- Name: countries; Type: ACL; Schema: facts; Owner: sms_admin
--

GRANT SELECT ON TABLE countries TO applicant;
GRANT SELECT ON TABLE countries TO mgt;


SET search_path = hr, pg_catalog;

--
-- Name: department; Type: ACL; Schema: hr; Owner: sms_admin
--

GRANT SELECT ON TABLE department TO hr;
GRANT SELECT,INSERT ON TABLE department TO applicant;


--
-- Name: login; Type: ACL; Schema: hr; Owner: sms_admin
--

GRANT SELECT ON TABLE login TO hr;
GRANT SELECT,INSERT ON TABLE login TO applicant;


--
-- Name: position; Type: ACL; Schema: hr; Owner: sms_admin
--

GRANT SELECT ON TABLE "position" TO hr;
GRANT INSERT ON TABLE "position" TO mgt;
GRANT SELECT,INSERT ON TABLE "position" TO applicant;


SET search_path = predef, pg_catalog;

--
-- Name: sys_class_duratn; Type: ACL; Schema: predef; Owner: root
--

GRANT SELECT ON TABLE sys_class_duratn TO ao;
GRANT SELECT ON TABLE sys_class_duratn TO mgt;


SET search_path = public, pg_catalog;

--
-- Name: application; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT,UPDATE ON TABLE application TO ao;
GRANT SELECT,INSERT,UPDATE ON TABLE application TO applicant;


--
-- Name: bioinfo; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT,UPDATE ON TABLE bioinfo TO ao;
GRANT SELECT,INSERT,UPDATE ON TABLE bioinfo TO applicant;


--
-- Name: candidate; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT,UPDATE ON TABLE candidate TO ao;
GRANT SELECT,INSERT,UPDATE ON TABLE candidate TO applicant;


--
-- Name: educationinfo; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT,INSERT ON TABLE educationinfo TO applicant;


--
-- Name: guardianinfo; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT,UPDATE ON TABLE guardianinfo TO ao;
GRANT SELECT,INSERT,UPDATE ON TABLE guardianinfo TO applicant;


--
-- Name: medicalinfo; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT,INSERT ON TABLE medicalinfo TO applicant;


--
-- Name: login; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT,INSERT ON TABLE login TO applicant;


--
-- Name: siblings; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT INSERT ON TABLE siblings TO applicant;


SET search_path = teaching, pg_catalog;

--
-- Name: student; Type: ACL; Schema: teaching; Owner: sms_admin
--

GRANT SELECT,INSERT,UPDATE ON TABLE student TO ao;
GRANT SELECT,INSERT,UPDATE ON TABLE student TO applicant;
GRANT ALL ON TABLE student TO mgt;


SET search_path = public, pg_catalog;

--
-- Name: studentinfo_v; Type: ACL; Schema: public; Owner: sms_admin
--

GRANT SELECT ON TABLE studentinfo_v TO ao;
GRANT SELECT ON TABLE studentinfo_v TO applicant;


SET search_path = teaching, pg_catalog;

--
-- Name: class; Type: ACL; Schema: teaching; Owner: sms_admin
--

GRANT SELECT,UPDATE ON TABLE class TO ao;
GRANT SELECT,INSERT ON TABLE class TO applicant;
GRANT ALL ON TABLE class TO mgt;


--
-- Name: classroom; Type: ACL; Schema: teaching; Owner: sms_admin
--

GRANT ALL ON TABLE classroom TO mgt;
GRANT SELECT,INSERT ON TABLE classroom TO applicant;


--
-- PostgreSQL database dump complete
--

