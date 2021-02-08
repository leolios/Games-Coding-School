<?php


namespace App\Controllers\Admin;


use App\Utils\Common;
use App\Utils\Utils;
use JetBrains\PhpStorm\Pure;
use App\Models\LevelsCategories as Level_categories_model;

class LevelsCategories extends Common
{
    #[Pure] public function __construct()
    {
        parent::__construct("Categories Levels");
    }

    public function show()
    {
        $categories = new Level_categories_model();
        $allCat = $categories->getAll($categories->levels_categories_schema);
        parent::getView("Admin/categories.twig", params: ["categories" => $allCat], sidebar: true);
    }

    public function create(array $form)
    {
        $categories = new Level_categories_model();
        $categories->levels_categories_schema->setName($form['name']);
        $categories->levels_categories_schema->setDescription($form['description']);
        $categories->create($categories->levels_categories_schema);
        Utils::goBack();
    }

    public function update($uuid, array $form)
    {
        $categories = new Level_categories_model();
        $categories->levels_categories_schema->setName($form['name']);
        $categories->levels_categories_schema->setDescription($form['description']);
        $categories->levels_categories_schema->setId($uuid);
        $categories->update($categories->levels_categories_schema);
        Utils::redirect("/admin/levels_cat");
    }

    public function delete($uuid)
    {
        $categories = new Level_categories_model();
        $categories->levels_categories_schema->setId($uuid);
        $categories->delete($categories->levels_categories_schema);
        Utils::redirect("/admin/levels_cat");
    }
}