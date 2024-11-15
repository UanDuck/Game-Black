-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2024 a las 19:03:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_c` int(10) NOT NULL,
  `total` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `id_u` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjeta`
--

CREATE TABLE `tarjeta` (
  `id_tj` int(10) NOT NULL,
  `nom_titular` varchar(100) DEFAULT NULL,
  `tipo_tj` enum('debito','credito') DEFAULT NULL,
  `fecha_venc` date DEFAULT NULL,
  `num_tj` varchar(20) DEFAULT NULL,
  `cvv` int(4) DEFAULT NULL,
  `id_u` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_u` int(10) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `nom_u` varchar(50) DEFAULT NULL,
  `ap_u` varchar(50) DEFAULT NULL,
  `am_u` varchar(50) DEFAULT NULL,
  `correo_u` varchar(100) DEFAULT NULL,
  `contrasenia_u` varchar(255) DEFAULT NULL,
  `tipo_u` enum('admin','user') DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_u`, `usuario`, `nom_u`, `ap_u`, `am_u`, `correo_u`, `contrasenia_u`, `tipo_u`, `telefono`) VALUES
(1, 'orlando ', 'test1', 'aptets', 'amtest1', 'ejemploxd@gmail.com', '$2y$10$FNOvR1kIQgpXDy09irESWOglj.jq1sF440AL.OwCpaXYukUlZZZFC', 'user', 5522113344),
(2, 'alehi', 'ale', 'ave', 'ara', 'alehi@gmail.com', '$2y$10$c8E.mGf0qUr0u/IXecssEe/xuN5PysH8JNytA8AanGF2FEUVAfBda', 'user', 5523232323);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vc`
--

CREATE TABLE `vc` (
  `id_v` int(10) DEFAULT NULL,
  `id_c` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `videojuegos`
--

CREATE TABLE `videojuegos` (
  `id_v` int(10) NOT NULL,
  `nom_v` varchar(100) DEFAULT NULL,
  `desc_v` text DEFAULT NULL,
  `fecha_lanz` date DEFAULT NULL,
  `clasif_v` varchar(100) DEFAULT NULL,
  `genero_v` varchar(100) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `imagen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `videojuegos`
--

INSERT INTO `videojuegos` (`id_v`, `nom_v`, `desc_v`, `fecha_lanz`, `clasif_v`, `genero_v`, `precio`, `imagen`) VALUES
(1, 'Call of Duty: Black Ops 6', 'Campaña cinemática individual, la mejor experiencia Multijugador de su clase y el épico regreso de Zombis por rondas', '2024-10-24', 'ESRB', 'Accion', 1399.67, 'imagenes/juegos/blackops.jpg'),
(2, 'God of War Ragnarök', 'Kratos y Atreus se embarcan en una mítica aventura en busca de respuestas y aliados antes de la llegada del Ragnarök', '2024-09-19', 'ESRB', 'Acción', 999.89, 'imagenes/juegos/godofwarragnarok.jpg'),
(3, 'DRAGON BALL: Sparking! ZERO', 'Lleva a un nuevo nivel el legendario estilo de juego de la serie Budokai Tenkaichi', '2024-10-10', 'ESRB', 'Acción', 1459.45, 'imagenes/juegos/dragonball.jpg'),
(4, 'Gears 5', 'En medio de una guerra sin cuartel, Kait Diaz se marcha para desentrañar su conexión con el enemigo y descubrir la auténtica amenaza para Sera: ella misma', '2019-09-09', 'ESRB', 'Aventura', 699.89, 'imagenes/juegos/gears5.jpg'),
(5, 'Halo Infinite', 'Amplia campaña de mundo abierto y una dinámica experiencia multijugador gratuita', '2021-11-15', 'ESRB', 'Free to Play', 1399.67, 'imagenes/juegos/haloinfinite.jpg'),
(6, 'Halo: Reach', 'Experimentaras la historia heroica de Noble Team, un grupo de espartanos, que a través de gran sacrificio y coraje, salvó innumerables vidas frente a probabilidades imposibles.', '2019-12-03', 'ESRB', 'Acción', 199.78, 'imagenes/juegos/haloreach.jpg'),
(7, 'Halo 4', 'El Master Chief regresa para enfrentarse a una maligna entidad ancestral en busca de venganza y aniquilación', '2020-11-16', 'ESRB', 'Acción', 799.45, 'imagenes/juegos/halo4.jpg'),
(8, 'Halo 3', 'El Jefe Maestro regresa para ponerle fin a la guerra entre el Covenant, el Flood y la raza humana en el dramático y trepidante cierre de la trilogía original de Halo. ', '2020-07-13', 'ESRB', 'Accion', 234.67, 'imagenes/juegos/halo3.jpg'),
(9, 'Halo 2', 'Tras la destrucción del Halo, la humanidad experimenta una victoria efímera. Ansiosos por vengarse, el Covenant emprende un ataque sorpresa contra la Tierra, pero no están preparados para derrotar a la flota del UNSC y se ven obligados a huir al desliespacio. ', '2020-05-12', 'ESRB', 'Accion', 823.32, 'imagenes/juegos/halo2.jpg'),
(10, 'Left 4 Dead 2', 'Jugarás como uno de los cuatro nuevos supervivientes, armado con un amplio y devastador arsenal de armas clásicas y mejoradas.', '2009-11-16', 'ESRB ', 'Accion', 123.34, 'imagenes/juegos/left4.jpg'),
(11, 'Counter-Strike 2', 'Durante las dos últimas décadas, Counter‑Strike ha proporcionado una experiencia competitiva de primer nivel para los millones de jugadores de todo el mundo que contribuyeron a darle forma. Ahora el próximo capítulo en la historia de CS está a punto de comenzar. Hablamos de Counter‑Strike 2.', '2012-08-21', 'ESBR', 'Accion', 10.23, 'imagenes/juegos/counter.jpg'),
(12, 'Rust', 'El único objetivo en Rust es sobrevivir. Todo quiere que mueras: la vida salvaje de la isla y otros habitantes, el medio ambiente, otros supervivientes. Haz lo que sea necesario para sobrevivir una noche más.\r\n', '2018-02-08', 'ESBR', 'Aventura', 422.24, 'imagenes/juegos/rust.jpg'),
(13, 'The Forest', 'Como único sobreviviente de un accidente de avión de pasajeros, te encontrarás en un bosque misterioso luchando por sobrevivir contra una sociedad de mutantes caníbales.\r\n\r\nConstruye, explora y sobrevive en este aterrador simulador de terror de supervivencia en primera persona.', '2010-07-22', 'ESBR', 'Aventura', 185.34, 'imagenes/juegos/theforest.jpg'),
(14, 'Battlefield™ 2042', 'Juega con un toque extremo y renovado a modos conocidos: Conquista, Asalto y Avance. Con una mayor letalidad, un HUD limitado y mucho más riesgo, este modo extremo con temática de terror no tendrá ni un ápice de piedad ni tregua contigo.', '2021-11-19', 'ESBR', 'Accion', 1400.34, 'imagenes/juegos/battlefield.jpg'),
(15, 'PUBG: BATTLEGROUNDS', 'Aterriza en posiciones estratégicas, saquea armas y suministros, y sobrevive para que vuestro equipo sea el único en pie en los distintos y variados campos de batalla.\r\n', '2017-12-21', 'ESRB', 'Accion', 32.34, 'imagenes/juegos/pubg.jpg'),
(16, 'Days Gone', '\"Days Gone es un juego de acción y aventura de mundo abierto ambientado en un entorno natural hostil dos años después de una devastadora pandemia global.\r\n', '2021-05-17', 'ESBR', 'Accion', 934.23, 'imagenes/juegos/daysgone.jpg'),
(17, 'Injustice™ 2', '“Un gran port de un juego de lucha brillante con una asombrosa cantidad de contenido más allá de las peleas multijugador. Esta es la mejor versión de lanzamiento de cualquier título de NetherRealm y, a su vez, ofrece a los jugadores de PC una gran experiencia para uno de los juegos de lucha más profundos y repletos de contenido de la historia”.\r\n', '2017-11-30', 'ESBR', 'Accion', 1643.53, 'imagenes/juegos/injustice.jpg'),
(18, 'LIMBO', '“Limbo es lo más perfecto que puede llegar a ser un juego”.\r\n', '2011-08-02', 'ESBR', 'Aventura', 124.32, 'imagenes/juegos/limbo.jpg'),
(19, 'Terraria', '¡Cava, lucha, explora, construye! Nada es imposible en este juego de aventuras repleto de acción. El mundo es tu lienzo y la tierra misma es tu pintura.\r\n', '2011-05-16', 'ESBR', 'Aventura', 132.43, 'imagenes/juegos/terraria.jpg'),
(20, 'Dead Space', 'Isaac Clarke es un ingeniero cualquiera con la misión de reparar la descomunal nave extractora USG Ishimura, pero descubrirá que algo ha ido terriblemente mal. La tripulación de la nave ha sido asesinada y su querida compañera Nicole está perdida en algún lugar a bordo.\r\n', '2023-01-27', 'ESBR', 'Accion', 1400.32, 'imagenes/juegos/deadspace.jpg'),
(21, 'Cyberpunk 2077', 'Cyberpunk 2077 es un RPG de aventura y acción de mundo abierto ambientado en la megalópolis de Night City, donde te pondrás en la piel de un mercenario o una mercenaria ciberpunk y vivirás su lucha a vida o muerte por la supervivencia. Mejorado y con contenido nuevo adicional gratuito. Personaliza tu personaje y tu estilo de juego a medida que aceptas trabajos, te labras una reputación y desbloqueas mejoras. Las relaciones que forjes y las decisiones que tomes darán forma al mundo que te rodea. Aquí nacen las leyendas. ¿Cuál será la tuya?\r\n', '2020-12-09', 'ESRB', 'Rol', 1300.32, 'imagenes/juegos/cyberpunk.jpg'),
(22, 'Call of Duty®: Black Ops III', 'EL MEJOR call of duty', '2015-11-05', 'ESBR', 'Accion', 1333.43, 'imagenes/juegos/blackops3.jpg'),
(23, 'Red Dead Redemption 2', 'Arthur Morgan y la banda de Van der Linde se ven obligados a huir. Con agentes federales y los mejores cazarrecompensas de la nación pisándoles los talones, la banda deberá atracar, robar y luchar para sobrevivir en su camino por el escabroso territorio del corazón de América. Mientras las divisiones internas aumentan y amenazan con separarlos a todos, Arthur deberá elegir entre sus propios ideales y la lealtad a la banda que lo vio crecer.\r\n', '2019-12-05', 'M', 'Aventura', 1232.23, 'imagenes/juegos/readdeadredeption.jpg'),
(24, 'Grand Theft Auto V', 'Explora el galardonado mundo de Los Santos y el condado de Blaine con una resolución de 4K y disfrutar del juego a 60 fotogramas por segundo', '2015-04-14', 'M', 'Aventura', 3000.56, 'imagenes/juegos/grandauto.jpg'),
(25, 'Forza Horizon 4', 'Las estaciones dinámicas lo cambian todo en el mejor festival automovilístico del mundo. Ve por cuenta propia o únete a otros equipos para explorar la hermosa e histórica Gran Bretaña en un mundo abierto compartido', '2021-03-09', 'E', 'Carreras', 1.499, 'imagenes/juegos/forza4.jpg'),
(26, 'Forza Horizon 5', 'Explora los coloridos paisajes del mundo abierto de México con una acción de conducción ilimitada y divertida en los mejores coches del mundo. Únete a una emocionante partida de persecución con nuestra nueva experiencia multijugador 5 contra 1: el Escondite', '2021-11-08', 'E', 'Ca', 1444.89, 'imagenes/juegos/forza5.jpg'),
(27, 'Minecraft Dungeons', '¡Ábrete camino luchando en un juego de aventura y acción emocionante inspirado en los juegos de mazmorras clásicos y ambientado en el universo de Minecraft!p', '2021-09-22', 'E', 'Aventura', 349.9, 'imagenes/juegos/minecrafdu.jpg'),
(28, 'Diablo® IV', 'Únete a la lucha por Santuario en Diablo® IV, la aventura de rol y acción definitiva. Vive la campaña alabada por la crítica y nuevo contenido de temporada', '2023-10-17', 'M', 'Rol', 999.67, 'imagenes/juegos/diablo.jpg'),
(29, 'ELDEN RING', 'Un nuevo juego de rol de ambiente fantástico ', '2022-02-24', 'M', 'Rol', 819.45, 'imagenes/juegos/eldenring.jpg'),
(30, 'Mortal Kombat 11', 'Mortal Kombat ha regresado mejor que nunca en esta entrega de la icónica saga', '2019-04-23', 'M', 'Accion', 799.55, 'imagenes/juegos/mortalkombat11.jpg'),
(31, 'Back 4 Blood', 'Los creadores de la aclamada saga de Left 4 Dead presentan Back 4 Blood, un emocionante juego de disparos en primera persona cooperativo', '2021-10-12', 'M', 'Accion', 1199.56, 'imagenes/juegos/back4blood.jpg'),
(32, 'DayZ', 'Es una tierra plagada de zombis infectados donde compites por recursos escasos', '2018-12-13', 'M', 'Aventura', 500.89, 'imagenes/juegos/dayz.jpg'),
(33, 'Mortal Kombat 1', '¡Abre paso a una nueva era de esta icónica saga con un nuevo sistema de kombate, modos de juego y fatalities!', '2023-09-19', 'M', 'Accion', 699.45, 'imagenes/juegos/mortalkombat1.jpg'),
(34, 'Resident Evil 7 Biohazard', 'El peligro y la soledad emanan de las decrépitas paredes de una granja abandonada en el sur de los EE. UU', '2017-01-23', 'M', 'Aventura', 399.67, 'imagenes/juegos/residentevil7.jpg'),
(35, 'Resident Evil 4', 'Con una mecánica de juego modernizada, una historia reimaginada y unos gráficos espectacularmente detallados, Resident Evil 4 supone el renacimiento de un gigante del mundo de los videojuegos', '2023-03-23', 'M', 'Accion', 780.56, 'imagenes/juegos/resident4.jpg'),
(36, 'Resident Evil 3', 'Jill Valentine es una de las pocas personas que quedan en Raccoon City que han sido testigos de las atrocidades de Umbrella', '2020-04-02', 'M', 'Accion', 799.55, 'imagenes/juegos/resident3.jpg'),
(37, 'Resident Evil 2', 'Uno de los juegos más icónicos de todos los tiempos, regresa completamente reinventado para las consolas de nueva generación', '2019-01-24', 'M', 'Accion', 799.55, 'imagenes/juegos/resident2.jpg'),
(38, 'World War Z', 'World War Z es un trepidante juego de disparos cooperativo en tercera persona para hasta 4 jugadores. Cuenta con enormes hordas de zombis y una acción brutal', '2021-09-21', 'M', 'Accion', 349.99, 'imagenes/juegos/worldwarz.jpg'),
(39, 'God of War', 'Kratos ha dejado atrás su venganza contra los dioses del Olimpo y vive ahora como un hombre en los dominios de los dioses y monstruos nórdicos', '2022-01-14', 'M', 'Aventura', 829.45, 'imagenes/juegos/godofwar.jpg'),
(40, 'Crash Bandicoot™ 4: It’s About Time', 'El juego aclamado por la criticaaa', '2022-10-18', 'E', 'Accion', 799.34, 'imagenes/juegos/crashbandicoot.jpg'),
(41, 'Subnautica 2', 'Sumérgete en una nueva aventura con Subnautica 2, un juego de supervivencia en aguas abiertas de los creadores de la serie Subnautica', '2024-11-01', 'M', 'Aventura', 3000.45, 'imagenes/juegos/subnautica2.jpg'),
(42, 'DOOM Eternal', 'Los ejércitos del infierno han invadido la Tierra. Ponte en la piel del Slayer en una épica campaña para un jugador y cruza dimensiones para detener la destrucción definitiva de la humanidad', '2020-03-19', 'M', 'Accion', 719.67, 'imagenes/juegos/doometernal.jpg'),
(43, 'Supermarket Simulator', 'Dirige tu propio supermercado. Abastece las estanterías, fija los precios a tu gusto, acepta pagos, contrata personal, amplía y diseña tu tienda', '2024-02-20', 'M', 'Simulador', 157.99, 'imagenes/juegos/supermarket.jpg'),
(44, 'Hollow Knight', '¡Forja tu propio camino en Hollow Knight! Una aventura épica a través de un vasto reino de insectos y héroes que se encuentra en ruinas', '2017-02-24', 'E', 'Accion', 178.99, 'imagenes/juegos/hollow.jpg'),
(45, 'Fallout 4', 'El juego más ambicioso hasta la fecha y la siguiente generación de mundos abiertos', '2015-11-09', 'M', 'Accion', 359.67, 'imagenes/juegos/fallout4.jpg'),
(46, 'Dale & Dawson Stationery Supplies', 'Juega como Gerente, Especialista o Vago en un entorno de oficina. Los Vagos se mezclan, imitando a los Especialistas para evitar las sospechas del Gerente. Descubre o causa engaños para ganar.', '2024-08-30', 'E', 'Rol', 100.99, 'imagenes/juegos/dale.jpg'),
(47, 'Cuphead', 'Juego de acción clásico estilo \"dispara y corre\" que se centra en combates contra el jefe. Inspirado en los dibujos animados de los años 30, los aspectos visual y sonoro están diseñados con esmero empleando las mismas técnicas de la época', '2017-09-29', 'E', 'Accion', 179.99, 'imagenes/juegos/cuphed.jpg'),
(48, 'Metro Exodus', 'Huye de las ruinas devastadas del metro de Moscú y embárcate en un viaje épico por todo el continente en las estepas de la Rusia postapocalíptica', '2019-02-14', 'M', 'Accion', 500.67, 'imagenes/juegos/metroexodus.jpg'),
(49, 'Tom Ghost Recon', 'Shooter táctico realista por equipos donde una cuidadosa planificación y ejecución son claves para la victoria', '2015-12-01', 'M', 'Accion', 788.9, 'imagenes/juegos/tomclacys.jpg'),
(50, 'Minecraft', 'El mejor juego para juagar con tus amigos, mjum, mjum ', '2009-05-17', 'E', 'Aventura', 50.56, 'imagenes/juegos/mine.jpg'),
(51, 'Hades', 'Desafía al dios de los muertos y protagoniza una salvaje fuga del Inframundo en este juego de exploración de mazmorras', '2020-09-17', 'T', 'Rol', 282.99, 'imagenes/juegos/hades.jpg'),
(52, 'Stardew Valley', 'Acabas de heredar la vieja parcela agrícola de tu abuelo de Stardew Valley. Decides partir hacia una nueva vida con unas herramientas usadas y algunas monedas', '2016-02-26', 'E', 'Rol', 149.99, 'imagenes/juegos/stardew.jpg'),
(53, 'Celeste', 'Ayuda a Madeline a sobrevivir a los demonios de su interior en su viaje hasta la cima de la montaña Celeste, en este ajustadísimo plataforma, obra de los creadores de TowerFall', '2018-01-25', 'E', 'Accion', 227.99, 'imagenes/juegos/celeste.jpg'),
(54, 'Among Us', 'Un juego de socialización local o en línea de trabajo en equipo y traición para 4 a 15 jugadores... ¡ambientado en el espacio!', '2018-11-16', 'E', 'Rol', 59.99, 'imagenes/juegos/among.jpg'),
(55, 'Portal 2', 'Diseña puzles cooperativos para ti y tus amigos', '2011-04-18', 'E', 'Aventura', 123.99, 'imagenes/juegos/portal2.jpg'),
(56, 'Monster Hunter: World', 'En Monster Hunter: World, la última entrega de la serie, podrás disfrutar de la mejor experiencia de juego, usando todos los recursos a tu alcance para acechar monstruos en un nuevo mundo rebosante de emociones y sorpresas', '2018-08-08', 'T', 'Accion', 599.89, 'imagenes/juegos/mosterhunter.jpg'),
(57, 'DARK SOULS™ III', 'Dark Souls continúa redefiniendo los límites con el nuevo y ambicioso capítulo de esta serie revolucionaria, tan aclamada por la crítica', '2016-04-11', 'M', 'Accion', 819.99, 'imagenes/juegos/darksouls.jpg'),
(58, 'Firewatch', 'Firewatch es un juego de misterio en primera persona para un jugador que se desarrolla en la selva de Wyoming', '2016-02-09', 'M', 'Aventura', 227.99, 'imagenes/juegos/firewatch.jpg'),
(59, 'Sekiro™: Shadows Die Twice', 'Traza tu propio camino hacia la venganza en la galardonada aventura de FromSoftware, creadores de Bloodborne y la saga Dark Souls', '2019-03-21', 'M', 'Aventura', 1299.99, 'imagenes/juegos/sekiro.jpg'),
(60, 'Outer Wilds', 'Título de mundo abierto, que se desarrolla en un enigmático sistema solar confinado a un bucle temporal infinito', '2020-06-18', 'E', 'Aventura', 269.99, 'imagenes/juegos/outer.jpg'),
(61, 'Ghost of Tsushima', 'Forja tu propio camino a través de esta aventura de acción en mundo abierto y descubre sus maravillas ocultas', '2024-05-16', 'M', 'Aventura', 999.99, 'imagenes/juegos/ghostoftsushima.jpg'),
(62, 'Returnal', 'ROMPE EL CICLO Lucha por sobrevivir en el shooter en tercera persona y disfruta de la historia de Selene en PC. Enfréntate a desafíos roguelike', '2023-02-15', 'T', 'Accion', 999.99, 'imagenes/juegos/returnal.jpg'),
(63, 'DEATH STRANDING', 'Tu misión será dar esperanza a la humanidad conectando a los últimos supervivientes de un país arrasado', '2022-03-30', 'M', 'Accion', 199.99, 'imagenes/juegos/deathstranding.jpg'),
(64, 'Horizon Zero Dawn', 'El juego aclamado por la crítica, con un impresionante y renovado apartado gráfico y más mejoras que nunca.', '2024-10-01', 'T', 'Accion', 829.99, 'imagenes/juegos/horizon.jpg'),
(65, 'Slay the Spire', 'Crea un mazo único, encuentra criaturas extrañas, descubre reliquias de inmenso poder y mata La Aguja', '2019-01-23', 'E', 'Estrategia', 290.99, 'imagenes/juegos/slaythespire.jpg'),
(66, 'Assassins Creed Valhalla', 'Ponte en la piel de Eivor, una leyenda vikinga en busca de gloria. Saquea a tus enemigos, haz prosperar tu asentamiento y consolida tu poder político', '2022-12-06', 'M', 'Accion', 2551.99, 'imagenes/juegos/assassinscreedvalhalla.jpg'),
(67, 'The Witcher 3: Wild Hunt', 'Eres Geralt de Rivia, cazador de monstruos. En un continente devastado por la guerra e infestado de criaturas, tu misión es encontrar a Ciri, la niña de la profecía, un arma viviente que puede alterar el mundo tal y como lo conocemos', '2015-05-18', 'M', 'Rol', 439.99, 'imagenes/juegos/thewicher.jpg'),
(68, 'Assassins Creed Mirage', 'Vive la historia de Basim, un astuto ladrón callejero que sufre visiones terribles y busca respuestas y justicia mientras recorre las bulliciosas calles del Bagdad del siglo IX', '2024-10-17', 'M', 'Accion', 1799.99, 'imagenes/juegos/asssamirage.jpg'),
(69, 'XCOM 2', 'Han pasado veinte años desde que la humanidad perdió la guerra contra los invasores alienígenas y hay un nuevo orden en la Tierra', '2016-02-04', 'T', 'Estrategia', 54.95, 'imagenes/juegos/xcom.jpg'),
(70, 'Half-Life 2 RTX', 'Aclamado por la crítica, como nunca antes, reimaginado con RTX Remix. Incluye trazado de rayos completo, recursos remasterizados ', '2024-11-06', 'T', 'Accion', 456.99, 'imagenes/juegos/half.jpg'),
(71, 'BioShock Infinite', 'Endeudado con la gente equivocada, con su vida en juego, veterano de la Caballería de los EE. UU', '2013-03-25', 'M', 'Accion', 269.99, 'imagenes/juegos/bio.jpg'),
(72, 'FINAL FANTASY XIV Online', '¡Forma equipo con tus amigos o juega en solitario! Adéntrate en todas las mazmorras de la historia principal en solitario y busca la ayuda de los NPC aliados para que luchen a tu lado.\r\n\r\n', '2014-02-18', 'T', 'Rol', 323.23, 'imagenes/juegos/header.jpg'),
(73, 'Planet Zoo', 'Crea un mundo de vida salvaje en Planet Zoo. De los desarrolladores de Planet Coaster y Zoo Tycoon, llega el simulador de zoológicos definitivo, con auténticos animales vivos que observan, sienten y exploran el mundo que tú generes a su alrededor. Vive una campaña por todo el mundo o deja volar tu imaginación con la libertad del modo Creación. Crea hábitats únicos y paisajes descomunales, toma decisiones importantes e influyentes, y cría tus animales durante la construcción y gestión de los zoológicos más salvajes del mundo.\r\n', '2019-11-05', 'E', 'Simulador', 1332.23, 'imagenes/juegos/planet.jpg'),
(74, 'Dead by Daylight', 'Dead by Daylight es un juego de horror de multijugador (4 contra 1) en el que un jugador representa el rol del asesino despiadado y los 4 restantes juegan como supervivientes que intentan escapar de él para evitar ser capturados, torturados y asesinados.\r\n', '2016-06-14', 'M', 'Accion', 543.34, 'imagenes/juegos/deadby.jpg'),
(75, 'No Mans Sky', 'Inspirado en las aventuras y la imaginación que adoramos de la ciencia ficción clásica, No Mans Sky te presenta toda una galaxia para que la explores, llena de singulares planetas y formas de vida, junto a peligro y acción constantes.\r\n', '2016-08-12', 'T', 'Aventura', 753.35, 'imagenes/juegos/sky.jpg'),
(76, 'Risk of Rain 2', 'Te esperan un montón de ubicaciones diseñadas al detalle. Todas están repletas de monstruos desafiantes y enormes jefes empeñados en poner fin a tu existencia. Ábrete paso hasta derrotar al jefe final y escapa o continúa la partida de forma indefinida para comprobar cuánto tiempo logras sobrevivir. Un sistema de ajuste exclusivo propicia que tanto tú como los enemigos os hagáis más fuertes de forma ilimitada en el transcurso de una partida.\r\n', '2020-08-11', 'T', 'Accion', 543.23, 'imagenes/juegos/risk.jpg'),
(77, 'Sid Meier’s Civilization® VI', 'N/A', '2016-10-20', 'E', 'Estrategia', 23.42, 'imagenes/juegos/civi.jpg'),
(78, 'Watch_Dogs® 2', 'Juega como Marcus Holloway, un joven y brillante hacker que vive en la cuna de la revolución tecnológica, la zona de la bahía de San Francisco.\r\nColabora con DedSec, un conocido grupo de hackers, para ejecutar el mayor pirateo de la historia; acaba con ctOS 2.0, un sistema operativo utilizado por un grupo de genios criminales para vigilar y manipular a los ciudadanos a gran escala.', '2016-11-28', 'M', 'Aventura', 1243.32, 'imagenes/juegos/watch.jpg'),
(79, 'Team Fortress 2', 'Uno de los juegos de acción online más popular, Team Fortress 2, publica constantemente actualizaciones gratuitas: nuevos modos de juego, mapas, equipamiento y lo que es más importante, sombreros. Nueve clases diferentes proporcionan un enorme abanico de habilidades tácticas y personalidades y se prestan a una gran variedad de habilidades del jugador.\r\n\r\n', '2007-10-10', 'ESBR', 'Accion', 213.21, 'imagenes/juegos/team.jpg'),
(80, 'Borderlands 3', '¡Vuelve el padre de los shooter-looter, con tropecientas mil armas y una aventura caótica! Arrasa a tus enemigos y descubre mundos inéditos con uno de los cuatro nuevos buscacámaras. Juega solo o con amigos para derribar a adversarios increíbles, hacerte con montones de botín y salvar tu hogar de la secta más cruel de la galaxia.\r\n', '2020-03-13', 'M', 'Accion', 126.32, 'imagenes/juegos/borner.jpg'),
(81, 'Hitman: Absolution™', 'HITMAN: ABSOLUTION sigue los pasos del asesino original mientras trabaja en su contrato más personal hasta la fecha. Traicionado por la Agencia y buscado por la policía, el Agente 47 se ve abocado a la búsqueda de la redención en un mundo corrupto y retorcido.\r\n', '2012-11-19', 'M', 'Accion', 233.23, 'imagenes/juegos/hitman.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_c`),
  ADD KEY `id_u` (`id_u`);

--
-- Indices de la tabla `tarjeta`
--
ALTER TABLE `tarjeta`
  ADD PRIMARY KEY (`id_tj`),
  ADD KEY `id_u` (`id_u`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_u`),
  ADD UNIQUE KEY `email` (`correo_u`);

--
-- Indices de la tabla `vc`
--
ALTER TABLE `vc`
  ADD KEY `id_v` (`id_v`),
  ADD KEY `id_c` (`id_c`);

--
-- Indices de la tabla `videojuegos`
--
ALTER TABLE `videojuegos`
  ADD PRIMARY KEY (`id_v`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_c` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tarjeta`
--
ALTER TABLE `tarjeta`
  MODIFY `id_tj` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_u` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `videojuegos`
--
ALTER TABLE `videojuegos`
  MODIFY `id_v` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `usuario` (`id_u`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tarjeta`
--
ALTER TABLE `tarjeta`
  ADD CONSTRAINT `tarjeta_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `usuario` (`id_u`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vc`
--
ALTER TABLE `vc`
  ADD CONSTRAINT `vc_ibfk_1` FOREIGN KEY (`id_v`) REFERENCES `videojuegos` (`id_v`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vc_ibfk_2` FOREIGN KEY (`id_c`) REFERENCES `compra` (`id_c`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
