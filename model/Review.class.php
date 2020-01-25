<?php

class Review extends Model
{

	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	public function getReviews()
	{
		return DB::Instance()->Select(
			'SELECT * FROM `reviews`
			INNER JOIN `users`
			USING (id_user)
			',[]);
	}

	public function createReview($id_user, $content)
	{
		return DB::Instance()->Insert(
			"INSERT INTO `reviews` (`id_user`, `content`) 
			VALUES (:id_user, :content)",
			[
				'id_user' => $id_user,
				'content' => $content
			]
		);
	}

	public function deleteReview($id_reviews)
	{
		return DB::Instance()->Delete(
			"DELETE FROM `reviews`
			WHERE id_reviews = :id_reviews",
			[
				'id_reviews' => $id_reviews
			]
		);
	}

	public function updateReview($id_reviews, $content)
	{
		return DB::Instance()->update(
			"UPDATE `reviews`
			SET content = :content
			WHERE id_reviews = :id_reviews",
			[
				'id_reviews' => $id_reviews,
				'content' => $content
			]
		);
	}
}
