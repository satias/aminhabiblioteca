-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Set-2024 às 17:08
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `aminhabiblioteca`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `adicionar_autor` (IN `p_first_name` VARCHAR(25), IN `p_last_name` VARCHAR(25), IN `p_nacionality` VARCHAR(100), IN `p_photo_url` VARCHAR(255), IN `p_field_pt` TEXT, IN `p_field_eng` TEXT, IN `p_birth_date` DATE, IN `p_death_date` DATE, IN `p_personal_site` VARCHAR(255), IN `p_wiki_page` VARCHAR(255), IN `p_facebook_link` VARCHAR(255), IN `p_twitter_link` VARCHAR(255), IN `p_instagram_link` VARCHAR(255), IN `p_reddit_link` VARCHAR(255), IN `p_tiktok_link` VARCHAR(255))   BEGIN
    DECLARE
        v_description_id INT DEFAULT NULL ;
        -- Verificar se p_field_pt ou p_field_eng não são nulos
        IF p_field_pt IS NOT NULL OR p_field_eng IS NOT NULL THEN
        -- Inserir na tabela translation_table e obter o ID do novo registro
    INSERT INTO translation_table(
        field_name_id,
        field_pt,
        field_eng
    )
VALUES(4, p_field_pt, p_field_eng) ;
-- Capturar o ID do novo registro
SET
    v_description_id = LAST_INSERT_ID() ;
    END IF ;
    -- Inserir o autor na tabela author
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
    -- Usar o ID da descrição, que pode ser NULL
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `adicionar_livro` (IN `p_title` VARCHAR(100), IN `p_description_pt` TEXT, IN `p_description_eng` TEXT, IN `p_internal_code` INT, IN `p_fcover_url` VARCHAR(255), IN `p_bcover_url` VARCHAR(255), IN `p_available` BIT, IN `p_physical_condition` INT, IN `p_release_date` DATE, IN `p_available_req` BIT, IN `p_language` VARCHAR(100), IN `p_publisher` VARCHAR(100), IN `p_isbn` INT, IN `p_page_number` INT, IN `p_edition_number` INT, IN `p_generos` JSON, IN `p_autor_id` INT)   BEGIN
    DECLARE v_description_id INT DEFAULT NULL;
    DECLARE v_book_id INT;
    DECLARE v_genre_id INT;
    DECLARE i INT DEFAULT 0;
    DECLARE json_length INT;
    
    -- Verificar se p_description_pt ou p_description_eng não são nulos
    IF p_description_pt IS NOT NULL OR p_description_eng IS NOT NULL THEN
        -- Inserir na tabela translation_table e obter o ID do novo registro
        INSERT INTO translation_table (
            field_name_id,
            field_pt,
            field_eng
        ) VALUES (3, p_description_pt, p_description_eng);
        
        -- Capturar o ID do novo registro
        SET v_description_id = LAST_INSERT_ID();
    END IF;
    
    -- Inserir o livro na tabela books
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
        v_description_id,  -- Usar o ID da descrição, que pode ser NULL
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
    
    -- Capturar o ID do livro recém-adicionado
    SET v_book_id = LAST_INSERT_ID();
    
    -- Verificar se p_generos não é NULL e tem elementos
    IF p_generos IS NOT NULL THEN
        SET json_length = JSON_LENGTH(p_generos);
        
        -- Iterar sobre cada gênero no JSON
        WHILE i < json_length DO
            -- Extrair o ID do gênero
            SET v_genre_id = CAST(JSON_UNQUOTE(JSON_EXTRACT(p_generos, CONCAT('$[', i, ']'))) AS UNSIGNED);
            
            -- Inserir o gênero na tabela book_genres
            IF v_genre_id IS NOT NULL THEN
                INSERT IGNORE INTO book_genres (book_id, genre_id)
                VALUES (v_book_id, v_genre_id);
            END IF;
            
            -- Incrementar o índice
            SET i = i + 1;
        END WHILE;
    END IF;
    
    -- Associar o autor ao livro se p_autor_id não for NULL
    IF p_autor_id IS NOT NULL THEN
        INSERT IGNORE INTO author_book (book_id, author_id)
        VALUES (v_book_id, p_autor_id);
    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apagar_autor` (IN `author_id` INT)   BEGIN
    DECLARE desc_id INT;

    -- Verificar se o autor tem uma descrição
    SELECT description INTO desc_id
    FROM author
    WHERE id = author_id;

    -- Apagar o autor da tabela author primeiro
    DELETE FROM author WHERE id = author_id;

    -- Se desc_id não for NULL, apagar a descrição da tabela translation_table
    IF desc_id IS NOT NULL THEN
        DELETE FROM translation_table WHERE id = desc_id;
    END IF;

    DELETE FROM author_book WHERE author_id = author_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apagar_livro` (IN `p_book_id` INT, OUT `success` INT)   BEGIN
    DECLARE description_id INT;

    -- Inicializar success como 0 (falha)
    SET success = 0;

    -- Verificar se existem reservas para o livro
    IF NOT EXISTS (SELECT 1 FROM reserves WHERE book_id = p_book_id) THEN
        -- Verificar se existem requests com status = 1 para o livro
        IF NOT EXISTS (SELECT 1 FROM requests WHERE book_id = p_book_id AND status = 1) THEN
            -- Verificar se existem multas com status = 1 associadas a requests com esse livro
            IF NOT EXISTS (
                SELECT 1 
                FROM fines f
                INNER JOIN requests r ON f.request_id = r.id
                WHERE r.book_id = p_book_id AND f.status = 1
            ) THEN
                -- Se não houver reservas, requests com status = 1, nem multas com status = 1 associadas ao livro, realizar as exclusões
                START TRANSACTION;
                
                -- Excluir da tabela author_book
                DELETE FROM author_book WHERE book_id = p_book_id;
                
                -- Excluir da tabela favorite_books
                DELETE FROM favorite_books WHERE book_id = p_book_id;
                
                -- Excluir da tabela book_genres
                DELETE FROM book_genres WHERE book_id = p_book_id;
                
                -- Obter a descrição do livro para verificar e possivelmente deletar na tabela translation_table
                SELECT description INTO description_id FROM books WHERE id = p_book_id;
                
                IF description_id IS NOT NULL THEN

                    -- Atualizar a descrição do livro para NULL
                    UPDATE books SET description = NULL WHERE id = p_book_id;
                    -- Deletar na tabela translation_table
                    DELETE FROM translation_table WHERE id = description_id;
                    
                    
                END IF;
                
                -- Atualizar os detalhes do livro
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
                -- Se tudo der certo, definir success como 1 (sucesso)
                SET success = 1;
            END IF;
        END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apagar_utilizador` (IN `userId` INT)   BEGIN
    -- Excluir respostas a tickets do usuário (filhos de tickets)
    DELETE FROM ticket_replies
    WHERE ticket_id IN (SELECT id FROM tickets WHERE user_id = userId);
    
    -- Excluir tickets do usuário
    DELETE FROM tickets
    WHERE user_id = userId;
    
    -- Excluir notificações associadas ao usuário
    DELETE FROM notifications
    WHERE user_id = userId;
    
    -- Excluir livros favoritos do usuário
    DELETE FROM favorite_books
    WHERE user_id = userId;

    -- Excluir reservas associadas ao usuário
    DELETE FROM reserves
    WHERE user_id = userId;
    
    -- Excluir multas associadas às solicitações do usuário
    DELETE FROM fines
    WHERE request_id IN (SELECT id FROM requests WHERE user_id = userId);

    -- Excluir solicitações (requests) associadas ao usuário
    DELETE FROM requests
    WHERE user_id = userId;

    -- Excluir o próprio usuário
    DELETE FROM users
    WHERE id = userId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_autor` (IN `p_author_id` INT, IN `p_first_name` VARCHAR(25), IN `p_last_name` VARCHAR(25), IN `p_nacionality` VARCHAR(100), IN `p_photo_url` VARCHAR(255), IN `p_desc_pt` TEXT, IN `p_desc_eng` TEXT, IN `p_birth_date` DATE, IN `p_death_date` DATE, IN `p_personal_site` VARCHAR(255), IN `p_wiki_page` VARCHAR(255), IN `p_facebook_link` VARCHAR(255), IN `p_twitter_link` VARCHAR(255), IN `p_instagram_link` VARCHAR(255), IN `p_reddit_link` VARCHAR(255), IN `p_tiktok_link` VARCHAR(255))   BEGIN
    DECLARE existing_desc_id INT;
    DECLARE new_desc_id INT;

    -- Atualizar o autor
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

    -- Verificar se alguma descrição foi fornecida
    IF p_desc_pt IS NOT NULL OR p_desc_eng IS NOT NULL THEN
        -- Verificar se o autor já tem uma descrição
        SELECT description INTO existing_desc_id
        FROM author
        WHERE id = p_author_id;

        -- Se já existe uma descrição, atualize-a
        IF existing_desc_id IS NOT NULL THEN
            UPDATE translation_table
            SET field_pt = COALESCE(p_desc_pt, field_pt),
                field_eng = COALESCE(p_desc_eng, field_eng)
            WHERE id = existing_desc_id;
        ELSE
            INSERT INTO translation_table (field_name_id, field_pt, field_eng)
            VALUES (4, p_desc_pt, p_desc_eng);

            -- Obter o ID da nova descrição (usando LAST_INSERT_ID())
            SET new_desc_id = LAST_INSERT_ID();

            -- Atualizar a tabela author com o novo ID de descrição
            UPDATE author
            SET description = new_desc_id
            WHERE id = p_author_id;
        END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_livro` (IN `p_book_id` INT, IN `p_title` VARCHAR(100), IN `p_desc_pt` TEXT, IN `p_desc_eng` TEXT, IN `p_internal_code` INT, IN `p_fcover_url` VARCHAR(255), IN `p_bcover_url` VARCHAR(255), IN `p_available` BIT, IN `p_physical_condition` INT, IN `p_release_date` DATE, IN `p_available_req` BIT, IN `p_language` VARCHAR(100), IN `p_publisher` VARCHAR(100), IN `p_isbn` INT, IN `p_page_number` INT, IN `p_edition_number` INT, IN `p_generos` JSON, IN `p_author_id` INT)   BEGIN
    DECLARE existing_desc_id INT;
    DECLARE new_desc_id INT;
    DECLARE genre_id INT;

    -- Atualizar o livro
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

    -- Verificar se alguma descrição foi fornecida
    IF p_desc_pt IS NOT NULL OR p_desc_eng IS NOT NULL THEN
        -- Verificar se o livro já tem uma descrição
        SELECT description INTO existing_desc_id
        FROM books
        WHERE id = p_book_id;

        -- Se já existe uma descrição, atualize-a
        IF existing_desc_id IS NOT NULL THEN
            UPDATE translation_table
            SET field_pt = COALESCE(p_desc_pt, field_pt),
                field_eng = COALESCE(p_desc_eng, field_eng)
            WHERE id = existing_desc_id;
        ELSE
            -- Inserir nova descrição na tabela de traduções
            INSERT INTO translation_table (field_name_id, field_pt, field_eng)
            VALUES (3, p_desc_pt, p_desc_eng);

            -- Obter o ID da nova descrição (usando LAST_INSERT_ID())
            SET new_desc_id = LAST_INSERT_ID();

            -- Atualizar a tabela books com o novo ID de descrição
            UPDATE books
            SET description = new_desc_id
            WHERE id = p_book_id;
        END IF;
    END IF;

    -- Verificar e atualizar a tabela author_book
    IF p_author_id IS NOT NULL THEN
        -- Verificar se já existe uma entrada para o book_id na tabela author_book
        IF EXISTS (
            SELECT 1 
            FROM author_book 
            WHERE book_id = p_book_id
        ) THEN
            -- Atualizar o autor associado ao livro
            UPDATE author_book
            SET author_id = p_author_id
            WHERE book_id = p_book_id;
        ELSE
            -- Inserir nova entrada na tabela author_book
            INSERT INTO author_book (book_id, author_id)
            VALUES (p_book_id, p_author_id);
        END IF;
    END IF;

    -- Verificar e atualizar a tabela book_genres
    IF p_generos IS NOT NULL THEN
        -- Deletar todas as entradas na tabela book_genres para o book_id
        DELETE FROM book_genres
        WHERE book_id = p_book_id;

        -- Inserir novos gêneros
        WHILE JSON_LENGTH(p_generos) > 0 DO
            -- Extrair o primeiro gênero do JSON array
            SET genre_id = JSON_EXTRACT(p_generos, '$[0]');

            -- Inserir o gênero na tabela book_genres
            INSERT INTO book_genres (book_id, genre_id)
            VALUES (p_book_id, genre_id);

            -- Remover o gênero processado do JSON array
            SET p_generos = JSON_REMOVE(p_generos, '$[0]');
        END WHILE;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `auto_requisition_notification` (IN `book_id` INT, IN `user_id` INT, IN `start_at` DATE)   BEGIN
    DECLARE book_title VARCHAR(100);
    DECLARE new_description_id INT;
    DECLARE date_start DATE;

    -- Recuperar o título do livro da tabela books
    SELECT title INTO book_title
    FROM books
    WHERE id = book_id;

    -- Inserir a descrição em português e inglês na tabela translation_table
    INSERT INTO translation_table (field_name_id, field_pt, field_eng)
    VALUES 
    (
        1, 
        CONCAT('Informamos que uma nova requisição de livro foi automaticamente criada para você. Detalhes da Requisição: Livro - ', book_id ,' / Data de Início até: ' , start_at), 
        CONCAT('Please be advised that a new book requisition has been automatically created for you. Requisition Details: Book - ', book_id ,' /  Start Date until: ' , start_at)
    );

    -- Obter o ID da nova descrição inserida
    SET new_description_id = LAST_INSERT_ID();

    -- Criar a notificação para o usuário com o type_id 2 e title_id 22
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `block_user_today_fines` ()   BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE user_id INT;
    DECLARE book_id INT;
    DECLARE cur CURSOR FOR
        SELECT u.id, r.book_id
        FROM users u
        JOIN requests r ON u.id = r.user_id
        JOIN fines f ON r.id = f.request_id
        WHERE f.start_at = CURDATE()  -- Garante que a multa foi criada hoje
        AND f.notification_created = 0;  -- Verifica se a notificação ainda não foi criada

    -- Declare the handler for cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO user_id, book_id;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Atualizar o status do usuário
        UPDATE users
        SET status = 0
        WHERE id = user_id;

        -- Criar a notificação para o usuário bloqueado
        CALL create_user_blocked_notification(book_id, user_id);

        -- Atualizar a multa para indicar que a notificação foi criada
        UPDATE fines
        SET notification_created = 1
        WHERE request_id IN (SELECT id FROM requests WHERE book_id = book_id);
    END LOOP;

    CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_expired_requests` ()   BEGIN
    -- Atualizar as requisições que expiraram
    UPDATE requests
    SET expired = 1
    WHERE status = 1 AND end_at < NOW();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_user_blocked_notification` (IN `book_id` INT, IN `user_id` INT)   BEGIN
    DECLARE book_title VARCHAR(100);
    DECLARE new_description_id INT;

    -- Recuperar o título do livro da tabela books
    SELECT title INTO book_title
    FROM books
    WHERE id = book_id;

    -- Inserir a descrição em português e inglês na tabela translation_table
    INSERT INTO translation_table (field_name_id, field_pt, field_eng)
    VALUES 
    (
        1, 
        CONCAT('A sua conta foi bloqueada devido a falha de entrega do livro: ', book_title), 
        CONCAT('Your account has been blocked due to failure to deliver the book: ', book_title)
    );

    -- Obter o ID da nova descrição inserida
    SET new_description_id = LAST_INSERT_ID();

    -- Criar a notificação para o usuário com o type_id 2 e title_id 21
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_request` (IN `request_id` INT)   BEGIN
    DELETE FROM requests
    WHERE id = request_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Listar_Requisicoes_LivrosAutores` (IN `p_user_id` INT)   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Remover_Requisicao` (IN `request_id` INT)   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Remover_Reserva` (IN `reserva_id` INT)   BEGIN
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
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_or_insert_fines` ()   BEGIN
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
END$$

--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `AddBusinessDays` (`num_days` INT) RETURNS DATE  BEGIN
    DECLARE counter INT DEFAULT 0;
    DECLARE today_date DATE;
    DECLARE day_of_week INT;

    -- Inicializar today_date com a data atual
    SET today_date = CURDATE();

    WHILE counter < num_days DO
        -- Adicionar um dia à today_date
        SET today_date = DATE_ADD(today_date, INTERVAL 1 DAY);
        -- Obter o dia da semana (1 = Domingo, 7 = Sábado)
        SET day_of_week = DAYOFWEEK(today_date);
        -- Verificar se o dia não é um fim de semana (Sábado ou Domingo)
        IF day_of_week NOT IN (1, 7) THEN
            -- Incrementar o contador apenas para dias úteis
            SET counter = counter + 1;
        END IF;
    END WHILE;
    
    RETURN today_date;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `SimpleFunction` () RETURNS INT(11)  BEGIN
    RETURN 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) DEFAULT NULL,
  `nacionality` varchar(100) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `description` int(11) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `death_date` date DEFAULT NULL,
  `personal_site` varchar(255) DEFAULT NULL,
  `wiki_page` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `reddit_link` varchar(255) DEFAULT NULL,
  `tiktok_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `author`
--

INSERT INTO `author` (`id`, `first_name`, `last_name`, `nacionality`, `photo_url`, `description`, `birth_date`, `death_date`, `personal_site`, `wiki_page`, `facebook_link`, `twitter_link`, `instagram_link`, `reddit_link`, `tiktok_link`) VALUES
(1, 'autor', 'nome', 'Português', 'ethanmiller.png', 4, '1989-01-12', '2016-07-06', 'https://www.google.pt/', 'https://pt.wikipedia.org/', 'https://www.facebook.com/', 'https://x.com/', 'https://www.instagram.com/', 'https://www.reddit.com/', 'https://www.tiktok.com/'),
(2, 'autor 2', 'nome', 'Português', 'ethanmiller.png', 113, '1989-01-12', NULL, 'https://www.google.pt/', NULL, 'https://www.facebook.com/', NULL, 'https://www.instagram.com/', NULL, 'https://www.tiktok.com/'),
(3, 'autor 3', 'nome', 'Português', 'ethanmiller.png', 114, '1989-01-12', '2016-07-06', NULL, 'https://pt.wikipedia.org/', NULL, 'https://x.com/', NULL, 'https://www.reddit.com/', NULL),
(4, 'Autor1', 'nome1', 'Nacionalidade1', 'ethanmiller.png', 115, '1990-05-15', NULL, 'https://www.google.pt/', NULL, 'https://www.facebook.com/', NULL, 'https://www.instagram.com/', NULL, 'https://www.tiktok.com/'),
(5, 'Autor2', 'nome2', 'Nacionalidade2', 'ethanmiller.png', 116, '1985-09-20', '2016-07-06', NULL, 'https://pt.wikipedia.org/', NULL, 'https://x.com/', NULL, 'https://www.reddit.com/', NULL),
(6, 'Autor3', 'nome3', 'Nacionalidade3', 'ethanmiller.png', 117, '1978-11-10', NULL, 'https://www.google.pt/', NULL, 'https://www.facebook.com/', NULL, 'https://www.instagram.com/', NULL, 'https://www.tiktok.com/'),
(7, 'Autor4', 'nome4', 'Nacionalidade4', 'ethanmiller.png', 118, '1982-03-25', '2016-07-06', NULL, 'https://pt.wikipedia.org/', NULL, 'https://x.com/', NULL, 'https://www.reddit.com/', NULL),
(8, 'Autor5', 'nome5', 'Nacionalidade5', 'ethanmiller.png', 119, '1995-07-30', NULL, 'https://www.google.pt/', NULL, 'https://www.facebook.com/', NULL, 'https://www.instagram.com/', NULL, 'https://www.tiktok.com/'),
(9, 'Autor6', 'nome6', 'Nacionalidade6', 'ethanmiller.png', 120, '1989-01-12', '2016-07-06', NULL, 'https://pt.wikipedia.org/', NULL, 'https://x.com/', NULL, 'https://www.reddit.com/', NULL),
(10, 'Autor7', 'nome7', 'Nacionalidade7', 'ethanmiller.png', 121, '1980-06-05', NULL, 'https://www.google.pt/', NULL, 'https://www.facebook.com/', NULL, 'https://www.instagram.com/', NULL, 'https://www.tiktok.com/');

-- --------------------------------------------------------

--
-- Estrutura da tabela `author_book`
--

CREATE TABLE `author_book` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` int(11) DEFAULT NULL,
  `internal_code` int(11) NOT NULL,
  `fcover_url` varchar(255) NOT NULL,
  `bcover_url` varchar(255) DEFAULT NULL,
  `available` tinyint(1) NOT NULL,
  `physical_condition` int(11) NOT NULL,
  `release_date` date DEFAULT NULL,
  `available_req` tinyint(1) NOT NULL,
  `language` varchar(100) NOT NULL,
  `publisher` varchar(100) DEFAULT 'UNKNOWN',
  `isbn` int(11) DEFAULT NULL,
  `page_number` int(11) DEFAULT NULL,
  `edition_number` int(11) DEFAULT 1,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `books`
--

INSERT INTO `books` (`id`, `title`, `description`, `internal_code`, `fcover_url`, `bcover_url`, `available`, `physical_condition`, `release_date`, `available_req`, `language`, `publisher`, `isbn`, `page_number`, `edition_number`, `created_at`, `deleted`) VALUES
(1, 'livro 1', 15, 123, 'bookcover1.png', 'bookcover2.png', 0, 5, NULL, 0, 'pt', 'pub', 159, 3, 1, '2024-07-01', 0),
(3, 'livro 2', 122, 1231, 'bookcover2.png', 'bookcover1.png', 1, 1, '2024-06-10', 1, '123', '123', 123, 123, 1, '2024-07-16', 0),
(5, 'livro 3', 123, 15234, 'bookcover1.png', 'bookcover2.png', 1, 1, '2024-06-10', 1, '123', '123', 123, 123, 1, '2024-07-05', 0),
(6, 'Livro 1', 124, 1001, 'bookcover1.png', 'bookcover2.png', 0, 3, '2023-05-15', 1, 'Português', 'Editora A', 9783, 300, 1, '2024-07-08', 0),
(7, 'Livro 2', 125, 1002, 'bookcover2.png', '', 0, 2, '2024-02-20', 0, 'Inglês', 'Publisher X', 9784, 280, 1, '2024-07-08', 0),
(8, 'Livro 3', 126, 1003, 'bookcover1.png', 'bookcover2.png', 1, 4, '2023-11-10', 1, 'Espanhol', 'Editorial Z', 9785, 350, 1, '2024-07-08', 0),
(9, 'Livro 4', 127, 1004, 'bookcover2.png', NULL, 1, 1, '2022-09-30', 1, 'Português', 'Editora B', 6, 240, 1, '2024-07-08', 0),
(10, 'Livro 5', 128, 1005, 'bookcover1.png', 'bookcover2.png', 0, 5, '2024-01-05', 0, 'Inglês', 'Publisher Y', 9787, 400, 1, '2024-07-08', 0),
(11, 'Livro 6', 129, 1006, 'bookcover2.png', NULL, 0, 0, '2024-04-15', 0, 'Francês', 'Éditeur C', 9788, 200, 1, '2024-07-08', 0),
(12, 'Livro 7', 130, 1007, 'bookcover1.png', 'bookcover2.png', 1, 3, '2023-08-20', 1, 'Português', 'Editora D', 9789, 320, 1, '2024-07-08', 0),
(13, 'Livro 8', 131, 1008, 'bookcover2.png', NULL, 1, 2, '2023-10-01', 1, 'Inglês', 'Publisher Z', 9780, 270, 1, '2024-07-08', 0),
(14, 'Livro 9', 132, 1009, 'bookcover1.png', 'bookcover2.png', 1, 4, '2022-12-15', 1, 'Espanhol', 'Editorial E', 97811, 380, 1, '2024-07-08', 0),
(15, 'Livro 10', 133, 1010, 'bookcover2.png', NULL, 0, 5, '2023-03-05', 0, 'Português', 'Editora F', 97812, 420, 1, '2024-07-08', 0),
(16, 'Livro 11', 134, 1011, 'bookcover1.png', 'bookcover2.png', 1, 1, '2024-06-20', 1, 'Inglês', 'Publisher G', 97813, 250, 1, '2024-07-08', 0),
(17, 'Livro 12', 135, 1012, 'bookcover2.png', NULL, 0, 0, '2024-07-01', 0, 'Francês', 'Éditeur H', 97814, 180, 1, '2024-07-08', 0),
(18, 'Livro 13', 136, 1013, 'bookcover1.png', 'bookcover2.png', 1, 3, '2023-09-10', 1, 'Português', 'Editora I', 97815, 300, 1, '2024-07-08', 0),
(19, 'Livro 14', 137, 1014, 'bookcover2.png', NULL, 1, 2, '2022-11-25', 1, 'Inglês', 'Publisher J', 97821, 260, 1, '2024-07-08', 0),
(20, 'Deleted book', NULL, 1015, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-07-08', 1),
(21, 'Deleted book', NULL, 999, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(22, 'Deleted book', NULL, 998, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(23, 'Deleted book', NULL, 997, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(27, 'Deleted book', NULL, 9999, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(30, 'Deleted book', NULL, 2345, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(31, 'Deleted book', NULL, 233, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(32, 'Deleted book', NULL, 111, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(33, 'Deleted book', NULL, 1243, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(34, 'Deleted book', NULL, 1000, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(37, 'Deleted book', NULL, 1100, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(42, 'Deleted book', NULL, 11010, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(43, 'Deleted book', NULL, 1102, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(44, 'Deleted book', NULL, 1103, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-28', 1),
(45, 'Deleted book', NULL, 321321, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-29', 1),
(46, 'Deleted book', NULL, 2334, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-29', 1),
(47, 'Deleted book', NULL, 555, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-29', 1),
(49, 'Deleted book', NULL, 2333, 'img', NULL, 0, 5, NULL, 0, 'UNKNOWN', NULL, NULL, NULL, NULL, '2024-08-29', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `book_genres`
--

CREATE TABLE `book_genres` (
  `book_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `book_genres`
--

INSERT INTO `book_genres` (`book_id`, `genre_id`) VALUES
(3, 4),
(6, 1),
(6, 2),
(7, 3),
(8, 4),
(8, 5),
(9, 6),
(10, 7),
(11, 1),
(12, 2),
(13, 3),
(14, 4),
(14, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `favorite_books`
--

CREATE TABLE `favorite_books` (
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `favorite_books`
--

INSERT INTO `favorite_books` (`book_id`, `user_id`) VALUES
(8, 10),
(18, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `fines`
--

CREATE TABLE `fines` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `start_at` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `notification_created` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fines`
--

INSERT INTO `fines` (`id`, `request_id`, `amount`, `status`, `start_at`, `payment_date`, `notification_created`) VALUES
(1, 1, 9.29, 1, '2024-07-30', NULL, 1),
(2, 9, 25.57, 1, '2024-07-30', NULL, 1),
(3, 10, 24.29, 1, '2024-07-30', NULL, 1),
(4, 11, 16.29, 0, '2024-07-30', '2024-08-15', 1),
(5, 12, 19.29, 1, '2024-07-30', NULL, 1),
(6, 13, 16.86, 1, '2024-07-30', NULL, 1),
(7, 14, 15.57, 1, '2024-07-30', NULL, 1),
(8, 15, 14.14, 1, '2024-07-30', NULL, 1),
(9, 18, 37.57, 1, '2024-07-30', NULL, 1),
(10, 16, 7.86, 0, '2024-07-22', '2024-07-22', 1),
(11, 33, 5.29, 0, '2024-07-22', '2024-07-22', 1),
(13, 25, 10.29, 1, '2024-07-30', NULL, 1),
(15, 35, 7.29, 1, '2024-08-30', NULL, 1),
(16, 46, 5.29, 1, '2024-08-30', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(1, 5),
(2, 6),
(3, 7),
(4, 8),
(5, 9),
(6, 10),
(7, 11),
(8, 12),
(9, 13),
(10, 14);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` int(11) NOT NULL,
  `description` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `notifications`
--

INSERT INTO `notifications` (`id`, `type_id`, `user_id`, `title`, `description`, `status`, `created_at`) VALUES
(1, 1, 2, 1, 2, 1, '2024-06-05 00:00:00'),
(2, 1, 6, 1, 2, 1, '2024-06-05 00:00:00'),
(3, 1, 7, 1, 2, 1, '2024-06-05 18:24:17'),
(4, 1, 8, 1, 2, 1, '2024-06-05 18:46:15'),
(5, 1, 9, 1, 2, 1, '2024-06-05 23:34:15'),
(6, 1, 10, 1, 2, 1, '2024-06-05 00:05:03'),
(94, 2, 10, 21, 102, 1, '2024-07-30 17:22:00'),
(95, 2, 17, 21, 103, 1, '2024-07-30 17:22:00'),
(96, 2, 11, 21, 104, 1, '2024-07-30 17:22:00'),
(97, 2, 12, 21, 105, 1, '2024-07-30 17:22:00'),
(98, 2, 13, 21, 106, 1, '2024-07-30 17:22:00'),
(99, 2, 14, 21, 107, 1, '2024-07-30 17:22:00'),
(100, 2, 15, 21, 108, 1, '2024-07-30 17:22:01'),
(101, 2, 16, 21, 109, 1, '2024-07-30 17:22:01'),
(102, 2, 16, 21, 110, 1, '2024-07-30 17:22:01'),
(103, 2, 10, 21, 111, 1, '2024-07-30 17:22:01'),
(104, 3, 2, 22, 112, 1, '2024-08-02 19:44:18'),
(105, 1, 19, 1, 2, 1, '2024-08-07 17:42:49'),
(107, 2, 11, 21, 156, 1, '2024-08-30 14:32:00'),
(108, 2, 11, 21, 157, 1, '2024-08-30 14:32:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications_type`
--

CREATE TABLE `notifications_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `notifications_type`
--

INSERT INTO `notifications_type` (`id`, `type_name`) VALUES
(1, 'new_account'),
(2, 'blocked_account'),
(3, 'auto_requisition');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `popular_books`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `popular_books` (
`book_id` int(11)
,`book_title` varchar(100)
,`front_cover` varchar(255)
,`back_cover` varchar(255)
,`internal_code` int(11)
,`num_requests` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `popular_genres`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `popular_genres` (
`genre_id` int(11)
,`genre_name_pt` longtext
,`genre_name_eng` longtext
,`num_requests` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `start_at` date NOT NULL,
  `end_at` date DEFAULT NULL,
  `review_status` tinyint(4) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT 0,
  `date_extended` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `book_id`, `status`, `start_at`, `end_at`, `review_status`, `expired`, `date_extended`) VALUES
(1, 10, 3, 0, '2024-07-01', '2024-07-31', 0, 1, 0),
(2, 10, 1, 0, '2024-07-10', '2024-07-17', 0, 0, 0),
(3, 11, 7, 0, '2024-01-10', '2024-01-17', 0, 0, 0),
(4, 12, 8, 0, '2024-01-20', '2024-01-27', 0, 0, 0),
(5, 13, 9, 0, '2024-02-05', '2024-02-12', 0, 0, 0),
(6, 14, 10, 0, '2024-02-10', '2024-02-17', 0, 0, 0),
(7, 15, 11, 0, '2024-03-05', '2024-03-12', 0, 0, 0),
(8, 16, 12, 0, '2024-03-15', '2024-03-22', 0, 0, 0),
(9, 17, 6, 0, '2024-04-01', '2024-04-08', 0, 1, 0),
(10, 11, 8, 0, '2024-04-10', '2024-04-17', 0, 1, 0),
(11, 12, 10, 0, '2024-05-05', '2024-05-12', 0, 1, 0),
(12, 13, 12, 0, '2024-05-15', '2024-05-22', 0, 1, 0),
(13, 14, 7, 0, '2024-06-01', '2024-06-08', 0, 1, 0),
(14, 15, 9, 0, '2024-06-10', '2024-06-17', 0, 1, 0),
(15, 16, 11, 0, '2024-06-20', '2024-06-27', 0, 1, 0),
(16, 17, 6, 1, '2024-06-25', '2024-07-02', 0, 1, 0),
(18, 16, 19, 0, '2024-01-01', '2024-01-15', 0, 1, 0),
(25, 10, 5, 1, '2024-07-17', '2024-07-24', 0, 1, 0),
(33, 10, 5, 0, '2024-07-17', '2024-07-20', 0, 1, 0),
(35, 11, 7, 1, '2024-07-17', '2024-08-14', 0, 1, 0),
(46, 11, 1, 1, '2024-07-17', '2024-08-28', 0, 1, 0),
(51, 8, 3, 1, '2024-07-31', NULL, 0, 0, 0),
(53, 10, 19, 1, '2024-08-01', '2024-11-29', 0, 0, 1),
(65, 2, 17, 1, '2024-08-08', NULL, 0, 0, 0),
(70, 10, 20, 0, '2024-08-29', '2024-08-27', 0, 0, 0),
(73, 10, 8, 1, '2024-09-02', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reserves`
--

CREATE TABLE `reserves` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `prolonged` tinyint(1) NOT NULL,
  `queue_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `reserves`
--

INSERT INTO `reserves` (`id`, `user_id`, `book_id`, `prolonged`, `queue_num`) VALUES
(81, 1, 5, 0, 1),
(82, 10, 6, 0, 1),
(87, 1, 17, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `created_at` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `type_id`, `title`, `description`, `created_at`, `status`) VALUES
(1, 10, 5, 'asdasd', 'asdasdasd', '2024-07-26', 1),
(2, 10, 3, 'asdfadsfgsdfg', 'sadfgsadfgsdafg', '2024-07-26', 1),
(3, 10, 3, 'çoilsdgçlskdfhngsçdl', '.,lsdkfgçsldkgf\nsdf~gsfdhgt sftd\nh', '2024-07-26', 0),
(7, 10, 2, 'teste final', 'teste final', '2024-07-28', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `replied_at` datetime(3) NOT NULL,
  `response` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `ticket_id`, `user_id`, `replied_at`, `response`) VALUES
(1, 3, 10, '2024-07-26 18:34:57.000', 'sfgdfgsdfg'),
(2, 3, 10, '2024-07-26 18:40:01.000', 'asgasdfga'),
(3, 3, 14, '2024-07-26 18:50:51.000', 'ola'),
(4, 3, 10, '2024-07-26 19:18:52.000', 'sdfghdsfghdfgh'),
(5, 3, 10, '2024-07-26 19:19:08.000', 'ultima \nresposta'),
(6, 2, 10, '2024-07-28 17:45:27.000', 'resposta ao ticket 2');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL,
  `type_name` int(11) NOT NULL,
  `admin_response` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `type_name`, `admin_response`) VALUES
(1, 16, 0),
(2, 17, 0),
(3, 18, 0),
(4, 19, 0),
(5, 20, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `translation_fields_name`
--

CREATE TABLE `translation_fields_name` (
  `id` int(11) NOT NULL,
  `field_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `translation_fields_name`
--

INSERT INTO `translation_fields_name` (`id`, `field_name`) VALUES
(1, 'notifications_title'),
(2, 'notifications_description'),
(3, 'book_description'),
(4, 'author_description'),
(5, 'genre_name'),
(6, 'ticket_type_name'),
(7, 'ticket_type_description');

-- --------------------------------------------------------

--
-- Estrutura da tabela `translation_table`
--

CREATE TABLE `translation_table` (
  `id` int(11) NOT NULL,
  `field_name_id` int(11) DEFAULT NULL,
  `field_pt` longtext DEFAULT NULL,
  `field_eng` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `translation_table`
--

INSERT INTO `translation_table` (`id`, `field_name_id`, `field_pt`, `field_eng`) VALUES
(1, 1, 'Novo Utilizador', 'New User'),
(2, 2, 'Bem-vindo à nossa plataforma! Estamos felizes por ter você conosco. Não se esqueça de preencher as suas informações no perfil antes de levantar o seu primeiro livro.', 'Welcome to our platform! We\'re delighted to have you with us. Don\'t forget to fill in your profile information before picking up your first book.'),
(3, 3, 'ola', 'hello'),
(4, 4, 'autor pt.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(5, 5, 'Ficção Científica', 'Science Fiction'),
(6, 5, 'Romance', 'Romance'),
(7, 5, 'Fantasia', 'Fantasy'),
(8, 5, 'Mistério', 'Mystery'),
(9, 5, 'Thriller', 'Thriller'),
(10, 5, 'Suspense', 'Suspense'),
(11, 5, 'Drama', 'Drama'),
(12, 5, 'Comédia', 'Comedy'),
(13, 5, 'História', 'History'),
(14, 5, 'Biografia', 'Biography'),
(15, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(16, 6, 'Conta - Problemas', 'Account - Issues'),
(17, 6, 'Interbibliotecas - Pedidos', 'Interlibrary - Requests'),
(18, 6, 'Investigação - Assistência', 'Research - Assistance'),
(19, 6, 'Multas - Problemas', 'Fines - Issues'),
(20, 6, 'Website - Bug', 'Website - Bug'),
(21, 1, 'Aviso de bloqueio da Conta', 'Account blocking notice'),
(22, 1, 'Requisição Automática', 'Auto Requisition'),
(102, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: livro 2', 'Your account has been blocked due to failure to deliver the book: livro 2'),
(103, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 1', 'Your account has been blocked due to failure to deliver the book: Livro 1'),
(104, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 3', 'Your account has been blocked due to failure to deliver the book: Livro 3'),
(105, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 5', 'Your account has been blocked due to failure to deliver the book: Livro 5'),
(106, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 7', 'Your account has been blocked due to failure to deliver the book: Livro 7'),
(107, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 2', 'Your account has been blocked due to failure to deliver the book: Livro 2'),
(108, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 4', 'Your account has been blocked due to failure to deliver the book: Livro 4'),
(109, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 6', 'Your account has been blocked due to failure to deliver the book: Livro 6'),
(110, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 14', 'Your account has been blocked due to failure to deliver the book: Livro 14'),
(111, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: livro 3', 'Your account has been blocked due to failure to deliver the book: livro 3'),
(112, 1, 'Informamos que uma nova requisição de livro foi automaticamente criada para você. Detalhes da Requisição: Livro - 17 / Data de Início até: 2024-08-08', 'Please be advised that a new book requisition has been automatically created for you. Requisition Details: Book - 17 /  Start Date until: 2024-08-08'),
(113, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(114, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(115, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(116, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(117, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(118, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(119, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(120, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(121, 4, 'autor pt.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.', 'autor eng.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum.'),
(122, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(123, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.');
INSERT INTO `translation_table` (`id`, `field_name_id`, `field_pt`, `field_eng`) VALUES
(124, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(125, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(126, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(127, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(128, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(129, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.');
INSERT INTO `translation_table` (`id`, `field_name_id`, `field_pt`, `field_eng`) VALUES
(130, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(131, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(132, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(133, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(134, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(135, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.');
INSERT INTO `translation_table` (`id`, `field_name_id`, `field_pt`, `field_eng`) VALUES
(136, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(137, 3, 'EM PT, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.', 'EM ENG, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus tincidunt dui. Aenean elementum, est vitae feugiat facilisis, eros eros iaculis arcu, a ornare est purus sit amet neque. Nulla vitae nunc a neque varius dictum vitae vel nunc. Suspendisse a nulla id odio imperdiet pharetra ac quis massa. Aliquam eu molestie dolor. Praesent vel lorem tortor. Nulla cursus, libero et dignissim elementum, nulla nisi aliquet purus, id hendrerit lacus nibh vitae eros. Mauris hendrerit tempus dictum. Duis quis molestie velit. Fusce eleifend tincidunt lacinia. Ut posuere condimentum ante ac venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit vel urna nec elementum. Etiam malesuada quam id ultricies vulputate. Vestibulum hendrerit scelerisque efficitur. Morbi leo tortor, bibendum ac arcu hendrerit, tempus vulputate nulla. Aenean vel libero ex. Aliquam interdum pharetra ex. Nullam imperdiet turpis eu pretium interdum. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra. Duis hendrerit orci sit amet orci rutrum luctus. Proin nisi leo, tincidunt ut lacus nec, porttitor cursus felis. Quisque ex eros, pretium ut libero sed, aliquam feugiat felis. Suspendisse bibendum ac nunc eu suscipit. Aliquam id sem a dui volutpat rhoncus. Maecenas dolor libero, fringilla id ullamcorper sed, rhoncus vel augue. Pellentesque id tincidunt purus. Morbi faucibus venenatis cursus. Sed ultricies nunc ut tellus varius viverra.'),
(148, 3, '123', NULL),
(149, 3, '123', NULL),
(152, 3, 'ot', '1100'),
(156, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: Livro 2', 'Your account has been blocked due to failure to deliver the book: Livro 2'),
(157, 1, 'A sua conta foi bloqueada devido a falha de entrega do livro: livro 1', 'Your account has been blocked due to failure to deliver the book: livro 1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `created_at` datetime(3) NOT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `number` int(9) DEFAULT NULL,
  `status_del` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `user_type_id`, `first_name`, `last_name`, `username`, `password`, `email`, `photo_url`, `address`, `postal_code`, `created_at`, `updated_at`, `status`, `number`, `status_del`) VALUES
(1, 2, NULL, NULL, 'asd', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'adr.satias@hotmail.com', NULL, NULL, NULL, '2024-06-05 18:06:19.000', NULL, 1, NULL, 0),
(2, 3, NULL, NULL, 'asdasd', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'satiashd@hotmail.com', NULL, NULL, NULL, '2024-06-05 18:07:23.000', NULL, 1, NULL, 0),
(3, 3, NULL, NULL, 'asdasdasd', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'asd@qwe.qwe', NULL, NULL, NULL, '2024-06-05 18:12:36.000', NULL, 1, NULL, 0),
(4, 3, NULL, NULL, 'asdqwe', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'asd@sd.asd', NULL, NULL, NULL, '2024-06-05 18:14:18.000', NULL, 1, NULL, 0),
(6, 3, NULL, NULL, '123', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'qwer@sd.asd', NULL, NULL, NULL, '2024-06-05 18:23:15.000', '2024-08-29 20:15:56.000', 1, NULL, 0),
(7, 3, NULL, NULL, '123123', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'qwer@123.asd', NULL, NULL, NULL, '2024-06-05 18:24:17.000', NULL, 1, NULL, 0),
(8, 3, NULL, NULL, 'qwerty', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'qwerty@qwerty.com', NULL, NULL, NULL, '2024-06-05 18:46:15.000', NULL, 1, NULL, 0),
(9, 3, NULL, NULL, 'asdqweasd', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'bruno.k.bs45@gmail.com', NULL, NULL, NULL, '2024-06-05 23:34:15.000', NULL, 1, 2147483647, 0),
(10, 1, '1234', '1234', 'cvb', '$2y$10$168i/A0x4SMpRJUSbw/EnOqqzgbUoexfp2cAOJ5Q.RY5BukqVveF2', '12345@1234.com', 'imagem de teste.jpg', '123', '1233-333', '2024-06-06 00:05:03.000', '2024-09-03 20:56:42.000', 1, 911111111, 0),
(11, 3, 'João', 'Silva', 'joaosilva', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'joao@email.com', NULL, NULL, NULL, '2024-07-08 17:22:30.000', NULL, 0, NULL, 0),
(12, 3, 'Maria', 'Santos', 'msantos', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'maria@email.com', NULL, NULL, NULL, '2024-07-08 17:22:30.000', '2024-08-07 17:07:17.000', 1, NULL, 0),
(13, 3, 'Pedro', 'Oliveira', 'poliveira', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'pedro@email.com', NULL, NULL, NULL, '2024-07-08 17:22:30.000', NULL, 0, NULL, 0),
(14, 3, 'Ana', 'Ferreira', 'aferreira', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'ana@email.com', NULL, NULL, NULL, '2024-07-08 17:22:30.000', NULL, 0, NULL, 0),
(15, 3, 'Carlos', 'Martins', 'cmartins', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'carlos@email.com', NULL, NULL, NULL, '2024-07-08 17:22:30.000', NULL, 0, NULL, 0),
(16, 3, 'Sofia', 'Ribeiro', 'sribeiro', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'sofia@email.com', NULL, NULL, NULL, '2024-07-08 17:22:30.000', NULL, 0, NULL, 0),
(17, 3, 'Miguel', 'Costa', 'mcosta', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'miguel@email.com', NULL, NULL, NULL, '2024-07-08 17:22:30.000', NULL, 0, NULL, 0),
(19, 2, 'nome2', 'teste2', 'userteste', '$2y$10$Q1ihrCaafrPKbgnZnBZKx.LKpiVYurbVD4dmrxPLZ.riZMry04Ara', 'teste2@teste2.com', 'imagem de teste.jpg', 'teste morada', '4444-444', '2024-08-07 17:42:49.000', '2024-08-17 17:06:04.000', 1, 933333333, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `user_type`
--

INSERT INTO `user_type` (`id`, `type_name`) VALUES
(1, 'admin'),
(2, 'staff'),
(3, 'user');

-- --------------------------------------------------------

--
-- Estrutura para vista `popular_books`
--
DROP TABLE IF EXISTS `popular_books`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `popular_books`  AS SELECT `b`.`id` AS `book_id`, `b`.`title` AS `book_title`, `b`.`fcover_url` AS `front_cover`, `b`.`bcover_url` AS `back_cover`, `b`.`internal_code` AS `internal_code`, count(`r`.`id`) AS `num_requests` FROM (`books` `b` left join `requests` `r` on(`b`.`id` = `r`.`book_id`)) WHERE `b`.`deleted` = 0 AND `r`.`status` = 0 GROUP BY `b`.`id`, `b`.`title`, `b`.`fcover_url`, `b`.`bcover_url`, `b`.`internal_code` ORDER BY count(`r`.`id`) DESC LIMIT 0, 3 ;

-- --------------------------------------------------------

--
-- Estrutura para vista `popular_genres`
--
DROP TABLE IF EXISTS `popular_genres`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `popular_genres`  AS SELECT `g`.`id` AS `genre_id`, `tt_pt`.`field_pt` AS `genre_name_pt`, `tt_eng`.`field_eng` AS `genre_name_eng`, count(`r`.`id`) AS `num_requests` FROM ((((`genres` `g` join `book_genres` `bg` on(`g`.`id` = `bg`.`genre_id`)) join `requests` `r` on(`bg`.`book_id` = `r`.`book_id`)) join `translation_table` `tt_pt` on(`g`.`name` = `tt_pt`.`id`)) join `translation_table` `tt_eng` on(`g`.`name` = `tt_eng`.`id`)) WHERE `r`.`status` = 0 AND `tt_pt`.`field_name_id` = 5 AND `tt_eng`.`field_name_id` = 5 GROUP BY `g`.`id`, `tt_pt`.`field_pt`, `tt_eng`.`field_eng` ORDER BY count(`r`.`id`) DESC LIMIT 0, 4 ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`),
  ADD KEY `description` (`description`);

--
-- Índices para tabela `author_book`
--
ALTER TABLE `author_book`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Índices para tabela `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_code` (`internal_code`),
  ADD KEY `description` (`description`);

--
-- Índices para tabela `book_genres`
--
ALTER TABLE `book_genres`
  ADD PRIMARY KEY (`book_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Índices para tabela `favorite_books`
--
ALTER TABLE `favorite_books`
  ADD PRIMARY KEY (`book_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Índices para tabela `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Índices para tabela `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `title` (`title`),
  ADD KEY `description` (`description`);

--
-- Índices para tabela `notifications_type`
--
ALTER TABLE `notifications_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `reserves`
--
ALTER TABLE `reserves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Índices para tabela `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Índices para tabela `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_name` (`type_name`);

--
-- Índices para tabela `translation_fields_name`
--
ALTER TABLE `translation_fields_name`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `translation_table`
--
ALTER TABLE `translation_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_name_id` (`field_name_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Índices para tabela `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de tabela `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de tabela `reserves`
--
ALTER TABLE `reserves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `translation_fields_name`
--
ALTER TABLE `translation_fields_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `translation_table`
--
ALTER TABLE `translation_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`description`) REFERENCES `translation_table` (`id`);

--
-- Limitadores para a tabela `author_book`
--
ALTER TABLE `author_book`
  ADD CONSTRAINT `author_book_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `author_book_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);

--
-- Limitadores para a tabela `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`description`) REFERENCES `translation_table` (`id`);

--
-- Limitadores para a tabela `book_genres`
--
ALTER TABLE `book_genres`
  ADD CONSTRAINT `book_genres_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `book_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

--
-- Limitadores para a tabela `favorite_books`
--
ALTER TABLE `favorite_books`
  ADD CONSTRAINT `favorite_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `favorite_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);

--
-- Limitadores para a tabela `genres`
--
ALTER TABLE `genres`
  ADD CONSTRAINT `genres_ibfk_1` FOREIGN KEY (`name`) REFERENCES `translation_table` (`id`);

--
-- Limitadores para a tabela `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `notifications_type` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`title`) REFERENCES `translation_table` (`id`),
  ADD CONSTRAINT `notifications_ibfk_4` FOREIGN KEY (`description`) REFERENCES `translation_table` (`id`);

--
-- Limitadores para a tabela `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `reserves`
--
ALTER TABLE `reserves`
  ADD CONSTRAINT `reserves_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `reserves_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `ticket_types` (`id`);

--
-- Limitadores para a tabela `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ticket_replies_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Limitadores para a tabela `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD CONSTRAINT `ticket_types_ibfk_1` FOREIGN KEY (`type_name`) REFERENCES `translation_table` (`id`);

--
-- Limitadores para a tabela `translation_table`
--
ALTER TABLE `translation_table`
  ADD CONSTRAINT `translation_table_ibfk_1` FOREIGN KEY (`field_name_id`) REFERENCES `translation_fields_name` (`id`);

--
-- Limitadores para a tabela `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `execute_check_expired_and_update_fines` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-07-01 01:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    CALL check_expired_requests();
    CALL update_or_insert_fines();
    CALL block_user_today_fines();
END$$

CREATE DEFINER=`root`@`localhost` EVENT `update_books_event` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-07-30 15:40:38' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Atualizar os livros onde physical_condition = 5
    UPDATE books
    SET available_req = 0,
        available = 0
    WHERE physical_condition = 0;
END$$

CREATE DEFINER=`root`@`localhost` EVENT `delete_expired_requests` ON SCHEDULE EVERY 1 DAY STARTS '2024-08-02 19:57:04' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE req_id INT;
    
    -- Cursor para selecionar os request_ids que atendem aos critérios
    DECLARE request_cursor CURSOR FOR
        SELECT id
        FROM requests
        WHERE end_at IS NULL
          AND start_at < CURDATE()
          AND status = 1;
    
    -- Handler para o final do cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
    
    -- Abrir o cursor
    OPEN request_cursor;
    
    -- Loop para percorrer os resultados do cursor
    read_loop: LOOP
        FETCH request_cursor INTO req_id;
        
        -- Se não houver mais resultados, sair do loop
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Chamar o procedimento Remover_Requisicao passando o req_id
        CALL Remover_Requisicao(req_id);
    END LOOP;
    
    -- Fechar o cursor
    CLOSE request_cursor;
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
