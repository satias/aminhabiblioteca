CREATE DEFINER=`root`@`localhost` FUNCTION `AddBusinessDays`(`num_days` INT) RETURNS date
BEGIN
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
END