hummus=> ALTER TABLE organization ALTER COLUMN is_implementer TYPE bool USING CASE WHEN is_implementer=0 THEN FALSE ELSE TRUE END;

 CREATE VIEW sql_full_report_project_contact AS
 select p.id AS project_id,p.code AS project_code,p.name AS project_name,s.id AS structure_id,s.code AS structure_code,s.description AS structure_description,e.id AS event_id,e.title AS event_title,
 to_char(e.start,'YYYY-mm-dd') AS event_date_start,
 extract(year from e.start) AS event_date_start_year,
 extract(month from e.start) AS event_date_start_month,
 extract(day from e.start) AS event_date_start_day,
 to_char(e.end,'YYYY-mm-dd') AS event_date_end,
 extract(year from e.end) AS event_date_end_year,
 extract(month from e.end) AS event_date_end_month,
 extract(day from e.end) AS event_date_end_day,
 e.country_id AS event_country_id,ctry_event.value AS event_country_code,ctry_event.name AS event_country_name,a.id AS attendance_id,c.id AS contact_id,c.name AS contact_name,c.last_name AS contact_lastname,c.sex AS contact_sex,c.document AS contact_document,c.birthdate AS contact_birthdate,c.education_id AS contact_education_id,edu.name AS contact_education,c.phone_personal AS contact_phone_personal,c.men_home AS contact_men_home,c.women_home AS contact_women_home,c.organization_id AS contact_organization_id,o.name AS contact_organization,c.country AS contact_country_code,
 (CASE WHEN c.country = '' THEN '' ELSE ctry.name END) AS contact_country,
 c.city AS contact_city,c.community AS contact_community,c.municipality AS contact_municipality,pc.date_entry_project AS contact_project_date_entry,pc.product AS contact_project_product,pc.area AS contact_project_area_farm,pc.development_area AS contact_project_dev_area,pc.age_development_plantation AS contact_project_age_dev_plantation,pc.productive_area AS contact_project_productive_area,pc.age_productive_plantation AS contact_project_age_prod_plantation,pc.yield AS contact_project_yield,oi.id AS organization_implementing_id,oi.name AS organization_implementing_name from ((((((((((project p left join structure s on((p.id = s.project_id))) left join event e on((s.id = e.structure_id))) left join data_list ctry_event on((ctry_event.id = e.country_id))) left join attendance a on((e.id = a.event_id))) left join contact c on((a.contact_id = c.id))) left join data_list edu on((edu.id = c.education_id))) left join organization o on((o.id = c.organization_id))) left join organization oi on(((oi.id = e.implementing_organization_id) and (oi.is_implementer)))) left join data_list ctry on((ctry.value = c.country))) left join project_contact pc on(((pc.contact_id = c.id) and (pc.project_id = p.id))))

hummus=# ALTER TABLE auth_item ALTER COLUMN updated_at TYPE timestamp USING TIMESTAMP 'epoch' + created_at * INTERVAL '1 second';
hummus=# ALTER TABLE auth_item ALTER COLUMN updated_at TYPE timestamp USING TIMESTAMP 'epoch' + updated_at * INTERVAL '1 second';
hummus=# ALTER TABLE auth_rule ALTER COLUMN updated_at TYPE timestamp USING TIMESTAMP 'epoch' + updated_at * INTERVAL '1 second';
hummus=# ALTER TABLE auth_rule ALTER COLUMN created_at TYPE timestamp USING TIMESTAMP 'epoch' + created_at * INTERVAL '1 second';
