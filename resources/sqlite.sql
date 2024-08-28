-- #!sqlite

-- #{ table
    -- #{ users
CREATE TABLE IF NOT EXISTS WardrobeUsers
(
    name           VARCHAR(32) PRIMARY KEY NOT NULL,
    wardrobe          TEXT DEFAULT ""
);
    -- #}
-- #}

-- #{ data
    -- #{ users
        -- #{ add
            -- # :name string
            -- # :wardrobe string ""
INSERT OR IGNORE INTO
WardrobeUsers(name, wardrobe)
VALUES (:name, :wardrobe);
		-- #}
	    -- #{ get
			-- # :name string
SELECT * FROM WardrobeUsers WHERE name = :name;
        -- #}
        -- #{ set
-- # :name string
-- # :wardrobe string ""
INSERT OR REPLACE INTO
WardrobeUsers(name, wardrobe)
VALUES (:name, :wardrobe);
		-- #}
		-- #{ getAll
SELECT * FROM WardrobeUsers;
        -- #}
    -- #}
-- #}