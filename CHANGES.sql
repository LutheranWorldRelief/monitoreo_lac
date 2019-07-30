hummus=> ALTER TABLE organization ALTER COLUMN is_implementer TYPE bool USING CASE WHEN is_implementer=0 THEN FALSE ELSE TRUE END;

hummus=# ALTER TABLE auth_item ALTER COLUMN updated_at TYPE timestamp USING TIMESTAMP 'epoch' + created_at * INTERVAL '1 second';
hummus=# ALTER TABLE auth_item ALTER COLUMN updated_at TYPE timestamp USING TIMESTAMP 'epoch' + updated_at * INTERVAL '1 second';
hummus=# ALTER TABLE auth_rule ALTER COLUMN updated_at TYPE timestamp USING TIMESTAMP 'epoch' + updated_at * INTERVAL '1 second';
hummus=# ALTER TABLE auth_rule ALTER COLUMN created_at TYPE timestamp USING TIMESTAMP 'epoch' + created_at * INTERVAL '1 second';
