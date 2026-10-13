-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 30, 2025 at 03:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted_orders`
--

CREATE TABLE `accepted_orders` (
  `id` int(11) NOT NULL,
  `tracking_id` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `pickup_date` date NOT NULL,
  `status` enum('Accepted','Declined') DEFAULT 'Accepted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_orders`
--

INSERT INTO `accepted_orders` (`id`, `tracking_id`, `product_name`, `quantity`, `total_price`, `pickup_date`, `status`, `created_at`) VALUES
(1, 'HM3PBZAUW6', 'Ciao Sugoi Crunchy Prebiotics Chicken Dry Cat Food 22gx5', 1, 119.00, '2025-03-30', 'Accepted', '2025-03-28 23:58:53'),
(3, 'FL16Q1SYI1', 'Dixie 1kg Original Pack - Tuna Flavor - All Breed Cats - Adult 1-7 years - Cat Dry Food - petpoultryph', 1, 205.00, '2025-03-30', 'Accepted', '2025-03-29 00:00:10'),
(5, 'SB3WL7RPAT', 'Dixie 1kg Original Pack - Tuna Flavor - All Breed Cats - Adult 1-7 years - Cat Dry Food - petpoultryph', 1, 205.00, '2025-03-29', 'Accepted', '2025-03-29 00:19:13'),
(6, '17UU9G428U', 'Princess Dry Cat Food 1kg', 1, 329.00, '2025-03-31', 'Accepted', '2025-03-29 00:20:36'),
(8, 'SRUE6ZG04S', 'Monello Adult Cat Mix (Salmon and Chicken Flavor)', 1, 133.33, '2025-03-30', 'Accepted', '2025-03-29 00:24:14'),
(9, 'SRUE6ZG04S', 'Monello Adult Cat Mix (Salmon and Chicken Flavor)', 1, 133.33, '2025-03-30', 'Accepted', '2025-03-29 00:24:14'),
(16, '7VWU4UBEAP', 'Ciao Sugoi Crunchy Prebiotics Chicken Dry Cat Food 22gx5', 1, 119.00, '2026-01-04', 'Accepted', '2025-03-30 02:26:42'),
(17, 'ZWLZO4ZWM6', 'Aozi Kitten Dry Cat Food 2.5kg', 1, 879.00, '2025-03-31', 'Accepted', '2025-03-30 05:29:42'),
(18, 'ZWLZO4ZWM6', 'Aozi Kitten Dry Cat Food 2.5kg', 1, 879.00, '2025-03-31', 'Accepted', '2025-03-30 05:29:42'),
(19, '9870AFN959', 'Ciao Sugoi Crunchy Prebiotics Chicken Dry Cat Food 22gx5', 1, 119.00, '2025-03-31', 'Accepted', '2025-03-30 05:35:33'),
(20, '9870AFN959', 'Ciao Sugoi Crunchy Prebiotics Chicken Dry Cat Food 22gx5', 1, 119.00, '2025-03-31', 'Accepted', '2025-03-30 05:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `addproducts`
--

CREATE TABLE `addproducts` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addproducts`
--

INSERT INTO `addproducts` (`id`, `product_name`, `category`, `price`, `stock`, `description`, `image_url`) VALUES
(19, 'Aozi Kitten Dry Cat Food 2.5kg', 'Food', 879.00, 34, 'Pure natural organic food\r\nEnriched with Taurine to help support eye and heart health for cats\r\nEasy to digest, protect intestine and stomach\r\nThe meat used by pet food is up to the freshness standard rich in high-quality protein, make the food delicious and nutrients.\r\nThe fruits and vegetables are up to the freshness standard and with balanced nutrients.\r\nSelect deep-sea fish providing enough protein, fat, nutrients and energy.\r\nLow-allergic and high quality beef for cats with allergic constitution.\r\nNo material will cause skin allergy. Urinary care formula less salt, less oil,less burden to liver and kidney.', 'uploads/aozi.jpg'),
(20, 'Ciao Sugoi Crunchy Prebiotics Chicken Dry Cat Food 22gx5', 'food', 119.00, 15, 'Ciao Sugoi Crunchy Plus Prebiotics cat food is formulated for cats of all ages and breeds.\r\nIt has 10 billion Lactic Acid Bacteria (Prebiotic), which helps keep the gut healthy and balanced. \r\nIdeal for cats who need extra immunity boost, have sensitive tummies, or bowel movement issues.', 'uploads/2.jpg'),
(21, 'Brit Care Adult Activity Support Dry Cat Food 400g', 'food', 269.00, 19, 'Brit Care Cat Grain-Free Adult Activity Support\r\nComplete food for outdoor cats and adult cats with high activity levels.\r\nA hypoallergenic grain-free formula with high-quality protein that helps keep muscles lean and strong. An optimal combination of vitamins and organic trace elements provides the energy required for an active outdoor life. Chondroprotectives support healthy teeth, joints, and bones for everyday hunting and play.\r\nChondroprotectives and calcium for healthy teeth and bones.', 'uploads/3.jpg'),
(22, 'Princess Dry Cat Food 1kg', 'food', 329.00, 17, '100% complete and balanced nutrition\r\nFor all life stages\r\nHighly palatable\r\nFortified with vitamins and minerals like Taurine and Niacin\r\nMade in USA\r\nIt can be fed dry or moistened according to your petâ€™s preference.', 'uploads/4.jpg'),
(24, 'Dixie 1kg Original Pack - Tuna Flavor - All Breed Cats - Cat Dry Food ', 'Food', 205.00, 20, 'Delicious tuna bites help keep your cat coming back meal after meal\r\nPrebiotic support your catâ€™s digestion', 'uploads/8857125890762.jpg'),
(25, 'Monello Adult Cat Mix (Salmon and Chicken Flavor)', 'food', 133.33, 12, 'Poultry viscera meal, Salmon meal Corn, corn grain, broken rice, soybean meat ,wheat bran, corn gluten meat, poultry fat, poultry liver hydrolyzed, flaxseed, wheat flour, dehydrated autolyzed beer yeast, SALMON OIL, sodium hexametaphospate, olive oil, refined sunflower, vitamins, minerals , taurine, yucca schidigera extract et al..', 'uploads/Monello_Gatos_Adultos_Salmao_Frango_direito.png'),
(26, 'Special Cat Mousse Beef and Liver Wet Cat Food 400g (2 cans)', 'food', 218.00, 59, 'Special Cat Wet Food\r\nMousse with Beef and Liver Flavor\r\nFor Adult Cats of All Breeds\r\nMade with fresh meat\r\nEnriched with vitamins A, E and D3\r\nRefrigerate unused portion and consume within 2 days\r\nBe sure to have a clean bowl of cool, fresh water available at all times', 'uploads/5.jpg'),
(27, 'Monge Special Cat URINARY Cat Dry Food 1.5kg', 'food', 340.00, 19, 'Formulated for adult cats\r\nHigh digestibility, high palatability\r\nFormulated for the reduction of struvite stone\r\nContains F.O.S & M.O.S to ensure the intestinal wellness', 'uploads/monge-special-cat-urinary-cat-dry-food.jpg'),
(28, 'Aozi Puppy Lamb Organic Apple Dry Dog Food 2.5kg', 'food', 839.00, 8, '', 'uploads/102767_3_236bee4a-2b60-4d2e-8b0b-91bff59ff09d_500x.jpg'),
(29, 'Beef Pro PUPPY dog (1 KG Repacked)', 'food', 190.00, 33, ' beef meal with high digestibility and palatability to optimize growth and development of your dogs.', 'uploads/ph-11134207-7r98o-lksr3wg5jgqn88.jfif'),
(30, 'Pedigree DentaStix Puppy (3-12 months) 56g (7 Sticks) Dog Dental Treats', 'food', 100.00, 45, 'Helps reduce tartar build-up\r\nUnique X-shape to clean in between the teeth & other hard-to-reach areas\r\nAbrasive and chewy texture\r\nActive ingredient: Zinc Sulphate and STPP\r\nHigh calcium, low fat and no sugar added', 'uploads/pedigree-dentastix-puppy-dog-dental-treats.jpg'),
(40, 'Bingo Dry Dog Food For Puppy 1kg', 'food', 90.00, 30, 'Bingo Puppy is an ideal complete dog food for puppies, active dogs and pregnant/nursing females.\r\n', 'uploads/BingoPuppy_front_new.png'),
(41, 'TOPBREED ADULT/PUPPY 1KG REPACKED', 'food', 85.00, 23, 'INGREDIENTS\r\n\r\nMeat Meal, Wheat Grains and Wheat-byproducts, Rice Middling/Bran, Soya Meal, Beef Tallow (Preserved with Tocopherol), Vegetable Oil, Nutritional Yeast, Animal Digest, Salt, Amino Acids, Yucca Extract, Vitamin and Mineral Premix, Omega 3 and 6 Fatty Acids, Antioxidants, Anti-mold, Enzymes\r\n', 'uploads/toop1.jpg'),
(42, 'AOZI Premium Natural Dog Food PURE NATURAL ORGANIC Dog Dry Food For Adult&Puppy dog treats 1KG', 'food', 160.00, 30, '**Product Description**:\r\n **Why Choose AOZI Dog Food?  **\r\n **100% Natural Ingredients**: Real meat as #1 ingredient, no artificial additives, grains, or fillers.\r\n **Complete Nutrition**: Rich in Omega-3/6 for shiny coats & healthy skin + probiotics for sensitive stomachs.\r\n**All Life Stages**: Balanced formula for puppies, adults, and senior dogs of all breeds.\r\n**Trusted Quality**: Formulated with veterinarians, meets AAFCO standards.', 'uploads/ph-11134207-7ras8-m6ngi918sh3pc4.webp'),
(43, 'Beef Pro Adult packed per 500g/1kg for dog', 'Food', 99.00, 24, 'BEEF PRO BREEDERS PACK FOR ADULT DOGS\r\n\r\n A High protein, high energy and highly digestible beef meal based dry dog food in an easy-to-chew, bite size form. Because for the highly digestible formula, even the most demanding nutritional requirements can be met by feeding less than other commercial dog foods. \r\n\r\n High density nutrition results in a lower feeding amount with firmer stools and reduced stool volume. A blend of natural beef flavors is basted each morsel so even the most difficult to please dog will dig into it.', 'uploads/sg-11134201-22120-wo6woq6p9blvb3@resize_w450_nl.webp'),
(44, 'PEDIGREE Adult Dry Dog Food Beef And Vegetable Flavor 500g', 'food', 108.00, 20, '', 'uploads/ph-11134201-7r98q-lvwwjx6ycl8h43.webp'),
(45, 'PET ONE 1.4kg Adult Dog Food', 'food', 209.00, 10, 'Adult Dog Food Maintenance is recommended for dogs of all breeds ages 1 to 7 years with moderate activity levels.\r\n\r\nBrand: Pet One\r\n1.4 kg\r\nAdult Dog Food\r\nLight Maintenance\r\nNaturally Preserved with Vitamin E\r\nComplete and Nutritional Balanced Meal\r\nRich in Amino Acids for optimum Development (from Banana)\r\nContains Omega 3 and 6', 'uploads/sg-11134201-7rd6w-m6rp2l4vifxd02.webp'),
(46, 'Goodest Dog Braised Beef & Liver W/ Veggies 80G Pack Of 24', 'food', 507.00, 10, 'Try New Goodest Dog Wet Dog Food! It is deliciously made with Real Meat to provide Optimum Nutrition and Strong Immunity for your furbabies. It is formulated with veterinarians and follows the standards of AAFCO (Association of American Feed Control Officials), to provide the optimum nutrition for dogs of all life stages.\r\n\r\n\r\n\r\nPlus, we made Goodest affurdable so you can give your beloved furbabies the goodest nourishment everyday!\r\n\r\n\r\n\r\nAvailable in Beef & Liver Steak, Grilled Chicken & Liver, and Braised Beef with veggie flavors!', 'uploads/sg-11134201-7rcer-m6821pw6citq2e.webp'),
(49, 'Pet Dog Bed Blanket', 'accessories', 95.00, 30, 'Pet Dog Bed Blanket Soft Fleece Cat Cushion Blanket Winter Warm Paw Print Pet Cats Cover Blanket For Small Medium Large Dogs Mat\r\n\r\n*It can help your pets feel comfortable.\r\n\r\n*Fabric soft and smooth.', 'uploads/bed or blanket.webp'),
(50, 'Pet Dog Cat Thick Stainless Steel Non-slip Feeding Bowl Pet Water bowl Dog and Cat Food Bowl 30cm', 'accessories', 89.00, 30, 'Instructionï¼š\r\n\r\n1.Selected stainless steel materials, combined with advanced polishing technology, the product has smooth surface, durability, resistance to falling and biting, and can be used with confidence without toxicity or odor.\r\n\r\n2.The bottom of the bowl is provided with a detachable anti-slip coil, which effectively prevents the bowl from turning over. The mute design makes pets eat more safely.\r\n\r\n3.After advanced polishing, the surface of the product is bright, smooth and free from dirt, which makes it bright and easy to clean, making you worry less.', 'uploads/ph-11134207-7r98z-lsbe2np965vo12@resize_w450_nl.webp'),
(51, 'WADEES Cat Scratching Post Sisal Creative For Cat Tree Tower DIY Scratch Furniture Cat Climbing Frame', '', 189.00, 20, 'Product: Cat Scratching Post\r\n\r\nMaterial: Sisal / Paper tube\r\n\r\nSize: Length 12.5cm / 25cm / 30cm / 38cm / 50cm, the diameter is 7cm\r\n\r\nColor:As picture show\r\n\r\nHole: M8 screw hole\r\n\r\nUsed for: Cat climbing post replacement posts, cat scratch posts\r\n\r\nPackage Included:1PC Cat Scratching Post\r\n\r\nFeatures:', 'uploads/sg-11134201-7rbmk-llagrqoq8azw56@resize_w450_nl.webp'),
(52, ' Dog Leash and Collar Heavy Duty Dog Harness and Leash Dog Leash Padded Handle and Highly Reflective', '', 78.00, 50, 'Size: Medium\r\nMeasurement: 1.5*120cm (Width*Length)\r\nAdjustable Range of Collar: 30-50CM\r\nApplicable Weight of Pet: 7-20kg\r\nMaterial: Nylon\r\nMade from strong Nylon rope with firm buckle\r\nPackage includes: 1 pc pet leash and collar with metal chain', 'uploads/ph-11134207-7r992-lsuynebkvyh04a.webp'),
(53, 'Hanging Bird Nest Natural Bamboo Bird Hut Outdoor Birdhouse Wren Nest Chickadee House Small Shelter for Finch Canary', 'accessories', 94.09, 30, 'Eco-friendly,durable,breathable,not easy to deform.', 'uploads/d6a7d0e39de08ad6c76e45028ca8c79f.jpg_720x720q80.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `product_name`, `price`, `quantity`, `total_price`) VALUES
(36, 24, 'Dixie 1kg Original Pack - Tuna Flavor - All Breed Cats - Cat Dry Food ', 205.00, 1, 205.00),
(37, 25, 'Monello Adult Cat Mix (Salmon and Chicken Flavor)', 133.33, 1, 133.33),
(38, 22, 'Princess Dry Cat Food 1kg', 329.00, 1, 329.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `tracking_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('Manager') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `first_name`, `last_name`, `email`, `phone`, `role`, `password`, `created_at`) VALUES
(1, 'Harold', 'Tulo', 'Harold123@gmail.com', '9887382832', 'Manager', '12345', '2025-03-26 16:35:13'),
(2, 'Kenny', 'Omas-as', 'kennyo@gmail.com', '09944746228', 'Manager', '123456789', '2025-03-28 11:23:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `middle_name`, `email`, `phone`, `dob`, `gender`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Chaidemaezia', 'Dongogan', 'Guimba', 'chaidemaziad@gmail.com', '9662219584', '2004-01-19', 'female', 'qwerty', '2025-03-28 23:56:41', '2025-03-29 00:36:32'),
(2, 'Ken', 'Omas-as', 'Ondap', 'kenomasas@gmail.com', '9974382309', '2003-07-21', 'male', '12345', '2025-03-29 00:40:22', '2025-03-29 00:58:06'),
(3, 'John Chris', 'Dacobor', '', 'jcdacobor@gmail.com', '9974382309', '2004-01-13', 'male', 'dacobor', '2025-03-29 15:43:23', '2025-03-29 15:43:23'),
(4, 'Harold', 'Tulod', 'Gamboa', 'dodongmwaa@gmail.com', '0997438209', '2004-03-23', 'male', '12345', '2025-03-30 05:27:56', '2025-03-30 05:27:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accepted_orders`
--
ALTER TABLE `accepted_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addproducts`
--
ALTER TABLE `addproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accepted_orders`
--
ALTER TABLE `accepted_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `addproducts`
--
ALTER TABLE `addproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

