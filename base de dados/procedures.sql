CREATE DEFINER=`root`@`localhost` PROCEDURE `adicionar_autor`(IN `p_first_name` VARCHAR(25), IN `p_last_name` VARCHAR(25), IN `p_nacionality` VARCHAR(100), IN `p_photo_url` VARCHAR(255), IN `p_field_pt` TEXT, IN `p_field_eng` TEXT, IN `p_birth_date` DATE, IN `p_death_date` DATE, IN `p_personal_site` VARCHAR(255), IN `p_wiki_page` VARCHAR(255), IN `p_facebook_link` VARCHAR(255), IN `p_twitter_link` VARCHAR(255), IN `p_instagram_link` VARCHAR(255), IN `p_reddit_link` VARCHAR(255), IN `p_tiktok_link` VARCHAR(255))
BEGIN
    DECLARE
        v_description_id INT DEFAULT NULL ;
        
        IF p_field_pt IS NOT NULL OR p_field_eng IS NOT NULL THEN
        
    INSERT INTO translation_table(
        field_name_id,
        field_pt,
        field_eng
    )
VALUES(4, p_field_pt, p_field_eng) ;

SET
    v_description_id = LAST_INSERT_ID() ;
    END IF ;
    
INSERT INTO author(
    first_name,
    last_name,
    nacionality,
    photo_url,
    description,
    birth_date,
    death_date,
    personal_site,
    wiki_page,
    facebook_link,
    twitter_link,
    instagram_link,
    reddit_link,
    tiktok_link
)
VALUES(
    p_first_name,
    p_last_name,
    p_nacionality,
    p_photo_url,
    v_description_id,
    
    p_birth_date,
    p_death_date,
    p_personal_site,
    p_wiki_page,
    p_facebook_link,
    p_twitter_link,
    p_instagram_link,
    p_reddit_link,
    p_tiktok_link
) ;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `adicionar_livro`(
    IN p_title VARCHAR(100),
    IN p_description_pt TEXT,
    IN p_description_eng TEXT,
    IN p_internal_code INT,
    IN p_fcover_url VARCHAR(255),
    IN p_bcover_url VARCHAR(255),
    IN p_available BIT,
    IN p_physical_condition INT,
    IN p_release_date DATE,
    IN p_available_req BIT,
    IN p_language VARCHAR(100),
    IN p_publisher VARCHAR(100),
    IN p_isbn INT,
    IN p_page_number INT,
    IN p_edition_number INT,
    IN p_generos JSON,
    IN p_autor_id INT 
)
BEGIN
    DECLARE v_description_id INT DEFAULT NULL;
    DECLARE v_book_id INT;
    DECLARE v_genre_id INT;
    DECLARE i INT DEFAULT 0;
    DECLARE json_length INT;
    
    
    IF p_description_pt IS NOT NULL OR p_description_eng IS NOT NULL THEN
        
        INSERT INTO translation_table (
            field_name_id,
            field_pt,
            field_eng
        ) VALUES (3, p_description_pt, p_description_eng);
        
        
        SET v_description_id = LAST_INSERT_ID();
    END IF;
    
    
    INSERT INTO books (
        title,
        description,
        internal_code,
        fcover_url,
        bcover_url,
        available,
        physical_condition,
        release_date,
        available_req,
        language,
        publisher,
        isbn,
        page_number,
        edition_number
    ) VALUES (
        p_title,
        v_description_id,  
        p_internal_code,
        p_fcover_url,
        p_bcover_url,
        p_available,
        p_physical_condition,
        p_release_date,
        p_available_req,
        p_language,
        p_publisher,
        p_isbn,
        p_page_number,
        p_edition_number
    );
    
    
    SET v_book_id = LAST_INSERT_ID();
    
    
    IF p_generos IS NOT NULL THEN
        SET json_length = JSON_LENGTH(p_generos);
        
        
        WHILE i < json_length DO
            
            SET v_genre_id = CAST(JSON_UNQUOTE(JSON_EXTRACT(p_generos, CONCAT('$[', i, ']'))) AS UNSIGNED);
            
            
            IF v_genre_id IS NOT NULL THEN
                INSERT IGNORE INTO book_genres (book_id, genre_id)
                VALUES (v_book_id, v_genre_id);
            END IF;
            
            
            SET i = i + 1;
        END WHILE;
    END IF;
    
    
    IF p_autor_id IS NOT NULL THEN
        INSERT IGNORE INTO author_book (book_id, author_id)
        VALUES (v_book_id, p_autor_id);
    END IF;

END

CREATE DEFINER=`root`@`localhost` PROCEDURE `apagar_autor`(IN author_id INT)
BEGIN
    DECLARE desc_id INT;

    
    SELECT description INTO desc_id
    FROM author
    WHERE id = author_id;

    
    DELETE FROM author WHERE id = author_id;

    
    IF desc_id IS NOT NULL THEN
        DELETE FROM translation_table WHERE id = desc_id;
    END IF;

    DELETE FROM author_book WHERE author_id = author_id;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `apagar_livro`(IN p_book_id INT, OUT success INT)
BEGIN
    DECLARE description_id INT;

    
    SET success = 0;

    
    IF NOT EXISTS (SELECT 1 FROM reserves WHERE book_id = p_book_id) THEN
        
        IF NOT EXISTS (SELECT 1 FROM requests WHERE book_id = p_book_id AND status = 1) THEN
            
            IF NOT EXISTS (
                SELECT 1 
                FROM fines f
                INNER JOIN requests r ON f.request_id = r.id
                WHERE r.book_id = p_book_id AND f.status = 1
            ) THEN
                
                START TRANSACTION;
                
                
                DELETE FROM author_book WHERE book_id = p_book_id;
                
                
                DELETE FROM favorite_books WHERE book_id = p_book_id;
                
                
                DELETE FROM book_genres WHERE book_id = p_book_id;
                
                
                SELECT description INTO description_id FROM books WHERE id = p_book_id;
                
                IF description_id IS NOT NULL THEN

                    
                    UPDATE books SET description = NULL WHERE id = p_book_id;
                    
                    DELETE FROM translation_table WHERE id = description_id;
                    
                    
                END IF;
                
                
                UPDATE books SET 
                        description = NULL,
                        title = 'Deleted book',
                        fcover_url = 'img',
                        bcover_url = NULL,
                        available = 0,
                        physical_condition = 5,
                        release_date = NULL,
                        available_req = 0,
                        language = 'UNKNOWN',
                        publisher = NULL,
                        isbn = NULL,
                        page_number = NULL,
                        edition_number = NULL,
                        deleted = 1                                    
                     WHERE id = p_book_id;

                COMMIT;
                
                SET success = 1;
            END IF;
        END IF;
    END IF;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `apagar_utilizador`(IN `userId` INT)
BEGIN
    
    DELETE FROM ticket_replies
    WHERE ticket_id IN (SELECT id FROM tickets WHERE user_id = userId);
    
    
    DELETE FROM tickets
    WHERE user_id = userId;
    
    
    DELETE FROM notifications
    WHERE user_id = userId;
    
    
    DELETE FROM favorite_books
    WHERE user_id = userId;

    
    DELETE FROM reserves
    WHERE user_id = userId;
    
    
    DELETE FROM fines
    WHERE request_id IN (SELECT id FROM requests WHERE user_id = userId);

    
    DELETE FROM requests
    WHERE user_id = userId;

    
    DELETE FROM users
    WHERE id = userId;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_autor`(IN `p_author_id` INT, IN `p_first_name` VARCHAR(25), IN `p_last_name` VARCHAR(25), IN `p_nacionality` VARCHAR(100), IN `p_photo_url` VARCHAR(255), IN `p_desc_pt` TEXT, IN `p_desc_eng` TEXT, IN `p_birth_date` DATE, IN `p_death_date` DATE, IN `p_personal_site` VARCHAR(255), IN `p_wiki_page` VARCHAR(255), IN `p_facebook_link` VARCHAR(255), IN `p_twitter_link` VARCHAR(255), IN `p_instagram_link` VARCHAR(255), IN `p_reddit_link` VARCHAR(255), IN `p_tiktok_link` VARCHAR(255))
BEGIN
    DECLARE existing_desc_id INT;
    DECLARE new_desc_id INT;

    
    UPDATE author
    SET first_name = p_first_name,
        last_name = p_last_name,
        nacionality = p_nacionality,
        photo_url = p_photo_url,
        birth_date = p_birth_date,
        death_date = p_death_date,
        personal_site = p_personal_site,
        wiki_page = p_wiki_page,
        facebook_link = p_facebook_link,
        twitter_link = p_twitter_link,
        instagram_link = p_instagram_link,
        reddit_link = p_reddit_link,
        tiktok_link = p_tiktok_link
    WHERE id = p_author_id;

    
    IF p_desc_pt IS NOT NULL OR p_desc_eng IS NOT NULL THEN
        
        SELECT description INTO existing_desc_id
        FROM author
        WHERE id = p_author_id;

        
        IF existing_desc_id IS NOT NULL THEN
            UPDATE translation_table
            SET field_pt = COALESCE(p_desc_pt, field_pt),
                field_eng = COALESCE(p_desc_eng, field_eng)
            WHERE id = existing_desc_id;
        ELSE
            INSERT INTO translation_table (field_name_id, field_pt, field_eng)
            VALUES (4, p_desc_pt, p_desc_eng);

            
            SET new_desc_id = LAST_INSERT_ID();

            
            UPDATE author
            SET description = new_desc_id
            WHERE id = p_author_id;
        END IF;
    END IF;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_livro`(
    IN `p_book_id` INT, 
    IN `p_title` VARCHAR(100), 
    IN `p_desc_pt` TEXT, 
    IN `p_desc_eng` TEXT, 
    IN `p_internal_code` INT, 
    IN `p_fcover_url` VARCHAR(255), 
    IN `p_bcover_url` VARCHAR(255), 
    IN `p_available` BIT, 
    IN `p_physical_condition` INT, 
    IN `p_release_date` DATE, 
    IN `p_available_req` BIT, 
    IN `p_language` VARCHAR(100), 
    IN `p_publisher` VARCHAR(100), 
    IN `p_isbn` INT, 
    IN `p_page_number` INT,
    IN `p_edition_number` INT,
    IN `p_generos` JSON,
    IN `p_author_id` INT
)
BEGIN
    DECLARE existing_desc_id INT;
    DECLARE new_desc_id INT;
    DECLARE genre_id INT;

    
    UPDATE books
    SET title = p_title,
        internal_code = p_internal_code,
        fcover_url = p_fcover_url,
        bcover_url = p_bcover_url,
        available = p_available,
        physical_condition = p_physical_condition,
        release_date = p_release_date,
        available_req = p_available_req,
        language = p_language,
        publisher = p_publisher,
        isbn = p_isbn,
        page_number = p_page_number,
        edition_number = p_edition_number
    WHERE id = p_book_id;

    
    IF p_desc_pt IS NOT NULL OR p_desc_eng IS NOT NULL THEN
        
        SELECT description INTO existing_desc_id
        FROM books
        WHERE id = p_book_id;

        
        IF existing_desc_id IS NOT NULL THEN
            UPDATE translation_table
            SET field_pt = COALESCE(p_desc_pt, field_pt),
                field_eng = COALESCE(p_desc_eng, field_eng)
            WHERE id = existing_desc_id;
        ELSE
            
            INSERT INTO translation_table (field_name_id, field_pt, field_eng)
            VALUES (3, p_desc_pt, p_desc_eng);

            
            SET new_desc_id = LAST_INSERT_ID();

            
            UPDATE books
            SET description = new_desc_id
            WHERE id = p_book_id;
        END IF;
    END IF;

    
    IF p_author_id IS NOT NULL THEN
        
        IF EXISTS (
            SELECT 1 
            FROM author_book 
            WHERE book_id = p_book_id
        ) THEN
            
            UPDATE author_book
            SET author_id = p_author_id
            WHERE book_id = p_book_id;
        ELSE
            
            INSERT INTO author_book (book_id, author_id)
            VALUES (p_book_id, p_author_id);
        END IF;
    END IF;

    
    IF p_generos IS NOT NULL THEN
        
        DELETE FROM book_genres
        WHERE book_id = p_book_id;

        
        WHILE JSON_LENGTH(p_generos) > 0 DO
            
            SET genre_id = JSON_EXTRACT(p_generos, '$[0]');

            
            INSERT INTO book_genres (book_id, genre_id)
            VALUES (p_book_id, genre_id);

            
            SET p_generos = JSON_REMOVE(p_generos, '$[0]');
        END WHILE;
    END IF;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `auto_requisition_notification`(IN `book_id` INT, IN `user_id` INT, IN `start_at` DATE)
BEGIN
    DECLARE book_title VARCHAR(100);
    DECLARE new_description_id INT;
    DECLARE date_start DATE;

    
    SELECT title INTO book_title
    FROM books
    WHERE id = book_id;

    
    INSERT INTO translation_table (field_name_id, field_pt, field_eng)
    VALUES 
    (
        1, 
        CONCAT('Informamos que uma nova requisição de livro foi automaticamente criada para você. Detalhes da Requisição: Livro - ', book_id ,' / Data de Início até: ' , start_at), 
        CONCAT('Please be advised that a new book requisition has been automatically created for you. Requisition Details: Book - ', book_id ,' /  Start Date until: ' , start_at)
    );

    
    SET new_description_id = LAST_INSERT_ID();

    
    INSERT INTO notifications (type_id, user_id, title, description, status, created_at)
    VALUES 
    (
        3, 
        user_id, 
        22, 
        new_description_id, 
        1, 
        NOW()
    );
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `block_user_today_fines`()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE user_id INT;
    DECLARE book_id INT;
    DECLARE cur CURSOR FOR
        SELECT u.id, r.book_id
        FROM users u
        JOIN requests r ON u.id = r.user_id
        JOIN fines f ON r.id = f.request_id
        WHERE f.start_at = CURDATE()  
        AND f.notification_created = 0;  

    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO user_id, book_id;
        IF done THEN
            LEAVE read_loop;
        END IF;

        
        UPDATE users
        SET status = 0
        WHERE id = user_id;

        
        CALL create_user_blocked_notification(book_id, user_id);

        
        UPDATE fines
        SET notification_created = 1
        WHERE request_id IN (SELECT id FROM requests WHERE book_id = book_id);
    END LOOP;

    CLOSE cur;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_expired_requests`()
BEGIN
    
    UPDATE requests
    SET expired = 1
    WHERE status = 1 AND end_at < NOW();
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_user_blocked_notification`(IN `book_id` INT, IN `user_id` INT)
BEGIN
    DECLARE book_title VARCHAR(100);
    DECLARE new_description_id INT;

    
    SELECT title INTO book_title
    FROM books
    WHERE id = book_id;

    
    INSERT INTO translation_table (field_name_id, field_pt, field_eng)
    VALUES 
    (
        1, 
        CONCAT('A sua conta foi bloqueada devido a falha de entrega do livro: ', book_title), 
        CONCAT('Your account has been blocked due to failure to deliver the book: ', book_title)
    );

    
    SET new_description_id = LAST_INSERT_ID();

    
    INSERT INTO notifications (type_id, user_id, title, description, status, created_at)
    VALUES 
    (
        2, 
        user_id, 
        21, 
        new_description_id, 
        1, 
        NOW()
    );
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_request`(IN `request_id` INT)
BEGIN
    DELETE FROM requests
    WHERE id = request_id;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `Listar_Requisicoes_LivrosAutores`(IN `p_user_id` INT)
BEGIN
    SELECT 
        r.id AS request_id,
        r.status,
        r.start_at,
        r.end_at,
        r.expired,
        r.date_extended,
        b.title,
        b.internal_code,
        b.fcover_url,
        b.bcover_url,
        b.language,
        b.publisher,
        b.page_number,
        a.id AS author_id,
        a.first_name,
        a.last_name
    FROM 
        requests r
    INNER JOIN 
        books b ON r.book_id = b.id
    LEFT JOIN 
        author_book ab ON b.id = ab.book_id
    LEFT JOIN 
        author a ON ab.author_id = a.id
    WHERE 
        r.user_id = p_user_id;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `Remover_Requisicao`(IN `request_id` INT)
BEGIN
    -- Variáveis para armazenar informações da requisição removida
    DECLARE book_id INT;
    DECLARE reserve_user_id INT;
    DECLARE reserve_id INT;
    DECLARE date_start DATE;

    -- Obter book_id da requisição a ser removida
    SELECT r.book_id
    INTO book_id
    FROM requests r
    WHERE r.id = request_id;

    -- Remover a requisição
    CALL delete_request(request_id);

    -- Verificar se existe uma reserva com queue_num = 1 para o mesmo book_id
    SELECT r.id, r.user_id
    INTO reserve_id, reserve_user_id
    FROM reserves r
    WHERE r.book_id = book_id
    AND r.queue_num = 1
    LIMIT 1;

    -- Se encontrou uma reserva com queue_num = 1
    IF reserve_id IS NOT NULL THEN
        -- Chamar a procedure Remover_Reserva
        CALL Remover_Reserva(reserve_id);
        
        -- Definir a data de início como 4 dias úteis a partir de hoje
        SET date_start = AddBusinessDays(4);
        
        -- Inserir uma nova requisição com os dados da reserva removida
        INSERT INTO requests (user_id, book_id, status, start_at)
        VALUES (
            reserve_user_id,
            book_id,
            1, -- Status definido como 1
            date_start
        );

        -- Enviar notificação sobre a requisição automática
        CALL auto_requisition_notification(book_id, reserve_user_id, date_start);
    END IF;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `Remover_Reserva`(IN `reserva_id` INT)
BEGIN
    -- Variáveis para armazenar o book_id da reserva a ser removida
    DECLARE book_id INT;

    -- Obter o book_id da reserva a ser removida
    SELECT r.book_id
    INTO book_id
    FROM reserves r
    WHERE r.id = reserva_id;

    -- Verifique se a reserva foi encontrada
    IF book_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Reserva não encontrada.';
    END IF;

    -- Remover a reserva
    DELETE FROM reserves
    WHERE id = reserva_id;

    -- Se houver outras reservas com o mesmo book_id, atualizar a próxima reserva na fila
    UPDATE reserves
    SET queue_num = 1
    WHERE book_id = book_id
    AND queue_num <> 1
    ORDER BY id
    LIMIT 1;
    
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_or_insert_fines`()
BEGIN
    -- Inserir novas multas para requisições expiradas que não têm multas registradas
    INSERT INTO fines (request_id, amount, status, start_at)
    SELECT
        r.id,
        5.00 + (DATEDIFF(NOW(), r.end_at) / 7) * 1.00 as amount,
        1 as status,
        NOW() as start_at
    FROM
        requests r
    LEFT JOIN
        fines f ON r.id = f.request_id
    WHERE
        r.expired = 1
        AND f.id IS NULL; -- Garante que não há multa já registrada para a requisição

    -- Atualizar multas existentes para requisições expiradas
    UPDATE fines f
    INNER JOIN requests r ON f.request_id = r.id
    SET f.amount = 5.00 + (DATEDIFF(NOW(), r.end_at) / 7) * 1.00
    WHERE
        r.expired = 1
        AND f.status = 1;
END