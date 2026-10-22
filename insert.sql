INSERT INTO `roles` 
	(`id`, `name`, `description`, `created_at`, `updated_at`) 
VALUES 
	(NULL, 'Admin', 'Perfil Master', '2024-03-28 07:01:17', NULL);

INSERT INTO `users` 
	(`id`, `name`, `email`, `password`, `active`, `access`) 
VALUES
	(1, 'Marcelo Borges', 'marcelo@timastertec.com.br', '$2y$12$vbTxwBurkwwKLomQLHo7q.C2mSHOuuz35KyJ/v5MPjG8Qxmu9p3zu', 1, 1);

INSERT INTO `role_user` (`id`, `role_id`, `created_at`, `updated_at`, `user_id`) VALUES (NULL, '1', '2024-03-28 07:09:32', '2024-03-28 07:09:32', '1');

INSERT INTO 
	`log_tipos` (`id`, `descricao`) 
VALUES 
	(1, 'SUCCESS'),
	(2, 'INFO'),
	(3, 'WARNING'),
	(4, 'ERROR');