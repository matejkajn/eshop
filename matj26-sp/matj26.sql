-- --------------------------------------------------------

--
-- Struktura tabulky `categories`
--

CREATE TABLE `categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Lavazza'),
(2, 'Tchibo'),
(3, 'Illy'),
(4, 'Dallmayr'),
(5, 'Segafredo Zanetti');

-- --------------------------------------------------------

--
-- Struktura tabulky `goods`
--

CREATE TABLE `goods` (
  `good_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_czech_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `goods`
--

INSERT INTO `goods` (`good_id`, `name`, `description`, `price`, `category_id`, `image`) VALUES
(1, 'Lavazza Qualità Oro', 'Středně pražená 100% arabika pro ty, kteří milují čisté aroma. Lavazza Qualità Oro je výběrem neobvykle sladkých kávových zrn. Intenzivní aromatická chuť pro opravdové milovníky kávy. Káva je vhodná pro všechny způsoby přípravy.', '499.00', 1, 'lavazza-qualita-oro-zrnkova.jpg'),
(2, 'Lavazza Super Crema', 'Káva se smetanově jemnou cremou a dlouhotrvající chutí? Začněte den s kávou Lavazza Super Crema. Jemnou a plnou chuť, tolik typickou pro italské kávy, oceníte nejen při přípravě v automatickém kávovaru.', '549.00', 1, 'lavazza-super-crema-zrnkova.jpg'),
(3, 'Lavazza Top Class', 'Ráno probudí, večer pohladí. Top Class je mezi kávami Lavazza skutečnou špičkou nejen podle jména. Dopřejte si kávu jemné chuti s minimem hořkosti a výrazným aroma. Je opravdu skvostná.', '549.90', 1, 'lavazza-top-class-zrnkova.jpg'),
(4, 'Lavazza ¡Tierra! Selection', 'Vaše oblíbená Lavazza ¡Tierra! v edici Selection! Odpočiňte si. Nebo se naopak potřebujete soustředit na důležitý úkol? Příjemná vůně kávy ¡Tierra! s nádechem čokolády umí obojí - s prvním douškem příjemně uvolní, s douškem posledním přinese energii a sílu.', '599.90', 1, 'lavazza-tierra-zrnkova.jpg'),
(5, 'Tchibo Barista Espresso', 'Výrazná káva s tóny hořké čokolády a oříšků, stupeň intenzity 5/5.', '499.50', 2, 'tchibo-barista-espresso-zrnkova.jpg'),
(6, 'Tchibo Feine Milde', 'Káva Tchibo Feine Milde je kvalitní 100% arabika s jemnou a vyváženou chutí.', '185.50', 2, 'tchibo-feine-milde-zrnkova.jpg'),
(7, 'Tchibo Caffè Crema', 'Směs arabik z vyšších oblastí v Latinské Ameriky a Brazílie. 100% arabika, silnější pražení pro intenzivnější chuť.', '139.90', 2, 'senseo-pody-tchibo-caffe-crema.jpg'),
(8, 'illy Classico (středně pražená) - zrnková', 'Směs výborné kávy, s plnou jasnou chutí, vhodná k přípravě všech variant káv. Skvělá volba pro ty, kteří při přípravě kávy upřednostňují čerstvě namletá zrna.', '199.00', 3, 'illy-stredne-prazena-zrnkova.jpg'),
(9, 'Illy Decaf (bez kofeinu)', 'Káva se stejnou chutí jako illy středně pražená, avšak s obsahem kofeinu pod 0,05 %, což odpovídá pouhé polovině zákonného limitu pro bezkofeinovou kávu.', '219.00', 3, 'illy-bez-kofeinu-zrnkova.jpg'),
(10, 'Illy Arabica Selection Brasile', 'Káva z regionu Cerrado Mineiro od farmáře Glaucia de Castra. Výrazná nasládlá chuť, plné tělo s tóny čokolády a lehkým náznakem karamelu a čerstvě upečeného chleba.', '166.00', 3, 'illy-monoarabica-brazil-zrnkova.jpg'),
(11, 'Dallmayr prodomo', 'Vybraná směs zrnkové kávy z nejjemnějších vybraných druhů odrůd pěstovaných ve vysokohorských oblastech.', '169.50', 4, 'dallmayr-prodomo-zrnkova.jpg'),
(12, 'Dallmayr Espresso Monaco', 'Vybraná zrna Arabika propůjčují tomuto espressu jeho pravou a nezaměnitelně jemnou chuť. Káva pražená speciálně pro přípravu v přístrojích na espresso podle originálního italského receptu. Intenzivní a jemně natrpklá, s typickým aroma Dallmayr a se sametově jemnou pěnou. Ušlechtilá a nezaměnitelně jemná chuť, 100% Arabika.', '599.90', 4, 'dallmayr-espresso-monaco-zrnkova.jpg'),
(13, 'Segafredo Espresso Casa', 'Silná káva pro každodenní povzbuzení. Vybraná směs zrn Arabiky a Robusty propůjčuje kávě Espresso Casa elegantní plné aroma, chuť s lehce čokoládovými tóny a bohatou cremu.', '399.00', 5, 'segafredo-zanetti-espresso-casa-zrnkova.jpg'),
(14, 'Segafredo Selezione Arabica', '100% arabica z oblastí střední a jižní Ameriky. Káva s lahodnou chutí, jemným aroma a typicky arabikovou cremou. Ovocitá květinová vůně a čokoládovo-ořechová chuť uspokojí všechny milovníky kávy, kteří si rádi vychutnávají pravé italské espresso.', '499.00', 5, 'segafredo-selezione-arabica-zrnkova.jpg'),
(23, '<b>Jonáš<b>', '<b>Jonáš<b>', '10.00', 3, 'tchibo-feine-milde-zrnkova.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `code` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `street` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `zip` varchar(6) COLLATE utf8mb4_czech_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8mb4_czech_ci NOT NULL,
  `state` set('potvrzená','pozastavená') COLLATE utf8mb4_czech_ci NOT NULL DEFAULT 'potvrzená'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `code`, `name`, `surname`, `email`, `date`, `street`, `city`, `zip`, `tel`, `state`) VALUES
(1, NULL, 830005839, '<b>Jonáš<b>', '<b>Matějka<b>', 'matejkovec@gmail.com', '2021-06-14 16:54:16', 'Vojanova 1000', 'Beroun', '26601', '722528408', 'potvrzená'),
(2, NULL, 440911517, 'Jonáš Matějka', 'Matějka', 'matejkovec@gmail.com', '2021-06-14 04:50:32', 'Vojanova 1000', 'Beroun', '26601', '722528408', 'potvrzená'),
(3, 9, 457731515, 'test', 'test', 'test@netservis.cz', '2021-07-01 16:23:14', 'test', 'test', '12345', '123456789', 'potvrzená');

-- --------------------------------------------------------

--
-- Struktura tabulky `single_order`
--

CREATE TABLE `single_order` (
  `order_id` int(11) NOT NULL,
  `good_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `single_order`
--

INSERT INTO `single_order` (`order_id`, `good_id`, `price`, `quantity`) VALUES
(1, 1, '499.00', 4),
(1, 2, '549.00', 1),
(1, 3, '549.90', 1),
(2, 1, '499.00', 2),
(2, 4, '599.90', 1),
(3, 1, '499.00', 1),
(3, 2, '549.00', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `role` set('user','admin') COLLATE utf8mb4_czech_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `role`) VALUES
(1, 'admin@email.tld', '$2y$10$8b4USsQAXIaBdS3fNygLZOu5GUUxQb15KJVoTLsiV7lumo6Z7StNO', 'admin'),
(2, 'test@email.tld', '$2y$10$z381ifwPDv.AbIOKrTCspOmidzx7PsTjxig8TpR4RilTEo507eyI.', 'user'),
(9, 'test@netservis.cz', '$2y$10$.74dq0lBXYyfl5xM4nTxF.geTIzc4jjTdtT.fXlQm5awE7yqqgtQ2', 'user');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Klíče pro tabulku `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`good_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Klíče pro tabulku `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`);

--
-- Klíče pro tabulku `single_order`
--
ALTER TABLE `single_order`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `good_id` (`good_id`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pro tabulku `goods`
--
ALTER TABLE `goods`
  MODIFY `good_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pro tabulku `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `single_order`
--
ALTER TABLE `single_order`
  ADD CONSTRAINT `single_order_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `single_order_ibfk_2` FOREIGN KEY (`good_id`) REFERENCES `goods` (`good_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
