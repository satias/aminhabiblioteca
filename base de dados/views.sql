-- Criação da view para os 3 livros mais populares por número de requisições
CREATE VIEW top_popular_books AS
SELECT
    b.id AS book_id,
    b.title AS book_title,
    b.fcover_url AS front_cover,
    b.bcover_url AS back_cover,
    b.internal_code,
    COUNT(r.id) AS num_requests
FROM books b
    LEFT JOIN requests r ON b.id = r.book_id
WHERE
    b.deleted = 0
AND 
    r.status = 0 -- Considerando apenas requisições concluídas
GROUP BY
    b.id,
    b.title,
    b.fcover_url,
    b.bcover_url,
    b.internal_code
ORDER BY COUNT(r.id) DESC
LIMIT 3;
-- Limitando a 3 livros mais populares
-- Criação da view para as 4 categorias mais populares por número de requisições
CREATE VIEW top_popular_categories AS
SELECT
    g.id AS genre_id,
    tt_pt.field_pt AS genre_name_pt,
    tt_eng.field_eng AS genre_name_eng,
    COUNT(r.id) AS num_requests
FROM
    genres g
    INNER JOIN book_genres bg ON g.id = bg.genre_id
    INNER JOIN requests r ON bg.book_id = r.book_id
    INNER JOIN translation_table tt_pt ON g.name = tt_pt.id -- Join com a tabela de tradução para nome em português
    INNER JOIN translation_table tt_eng ON g.name = tt_eng.id -- Join com a tabela de tradução para nome em inglês
WHERE
    r.status = 0 -- Considerando apenas requisições concluídas
    AND tt_pt.field_name_id = 5 -- Filtra apenas os campos de gênero em português
    AND tt_eng.field_name_id = 5 -- Filtra apenas os campos de gênero em inglês
GROUP BY
    g.id,
    tt_pt.field_pt,
    tt_eng.field_eng
ORDER BY COUNT(r.id) DESC
LIMIT 4;
-- Limitando a 4 categorias mais populares