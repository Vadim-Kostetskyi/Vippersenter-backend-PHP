<?php

require_once 'route.php';

function handleRequest(string $method, string $uri, mysqli $mysql): void {
  global $apiBasePath; 

  $baseProductsPath = rtrim($apiBasePath, '/').'/products';
  $baseUsersPath = rtrim($apiBasePath, '/'). '/users';


  switch ($method) {
    case 'GET':
      if ($uri === $baseProductsPath) {
          $result = $mysql->query("SELECT * FROM `products`");
        
          $products = [];
          while ($row = $result->fetch_assoc()) {
              $products[] = $row;
          }
          
          foreach ($products as &$product) {
              $slug = $product['slug'];
              $stmtAttr = $mysql->prepare("SELECT attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity FROM product_attributes WHERE slug = ?");
              $stmtAttr->bind_param("s", $slug);
              $stmtAttr->execute();
              $resultAttr = $stmtAttr->get_result();
            
              $attributes = [];
              while ($attrRow = $resultAttr->fetch_assoc()) {
                  $attributes[] = $attrRow;
              }
              
              $product['attributes'] = $attributes;
              
              $stmtAttr->close();
          }
          unset($product);
          
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($products);
          exit;
          
        } elseif ($uri === rtrim($baseProductsPath, '/').'/popularProduct') {
        $result = $mysql->query("SELECT * FROM `products` WHERE popularProduct = 1");
    
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    
        foreach ($products as &$product) {
            $slug = $product['slug'];
            $stmtAttr = $mysql->prepare("SELECT attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity FROM product_attributes WHERE slug = ?");
            $stmtAttr->bind_param("s", $slug);
            $stmtAttr->execute();
            $resultAttr = $stmtAttr->get_result();
    
            $attributes = [];
            while ($attrRow = $resultAttr->fetch_assoc()) {
                $attributes[] = $attrRow;
            }
            $product['attributes'] = $attributes;
    
            $stmtAttr->close();
        }
        unset($product);
    
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($products);
        exit;
    } 
    elseif ($uri === rtrim($baseProductsPath, '/').'/newProduct') {
      $result = $mysql->query("SELECT * FROM `products` WHERE newProduct = 1");
  
      $products = [];
      while ($row = $result->fetch_assoc()) {
          $products[] = $row;
      }
  
      foreach ($products as &$product) {
          $slug = $product['slug'];
          $stmtAttr = $mysql->prepare("SELECT attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity FROM product_attributes WHERE slug = ?");
          $stmtAttr->bind_param("s", $slug);
          $stmtAttr->execute();
          $resultAttr = $stmtAttr->get_result();
  
          $attributes = [];
          while ($attrRow = $resultAttr->fetch_assoc()) {
              $attributes[] = $attrRow;
          }
          $product['attributes'] = $attributes;
  
          $stmtAttr->close();
      }
      unset($product);
  
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($products);
      exit;
    }  elseif (strpos($uri, rtrim($baseProductsPath, '/') . '/search=') === 0) {
      $searchTerm = urldecode(substr($uri, strlen(rtrim($baseProductsPath, '/') . '/search=')));
  
      $stmt = $mysql->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%')");
      $stmt->bind_param("s", $searchTerm);
      $stmt->execute();
      $result = $stmt->get_result();
  
      $products = [];
      while ($row = $result->fetch_assoc()) {
          $products[] = $row;
      }
  
      foreach ($products as &$product) {
          $slug = $product['slug'];
          $stmtAttr = $mysql->prepare("SELECT attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity FROM product_attributes WHERE slug = ?");
          $stmtAttr->bind_param("s", $slug);
          $stmtAttr->execute();
          $resultAttr = $stmtAttr->get_result();
  
          $attributes = [];
          while ($attrRow = $resultAttr->fetch_assoc()) {
              $attributes[] = $attrRow;
          }
  
          $product['attributes'] = $attributes;
          $stmtAttr->close();
      }
      unset($product);
  
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($products);
      exit;
  }
  
    elseif ($uri === rtrim($baseProductsPath, '/').'/randomProducts') {
        $result = $mysql->query("SELECT * FROM `products` ORDER BY RAND() LIMIT 3");
    
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    
        foreach ($products as &$product) {
            $slug = $product['slug'];
            $stmtAttr = $mysql->prepare("SELECT attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity FROM product_attributes WHERE slug = ?");
            $stmtAttr->bind_param("s", $slug);
            $stmtAttr->execute();
            $resultAttr = $stmtAttr->get_result();
    
            $attributes = [];
            while ($attrRow = $resultAttr->fetch_assoc()) {
                $attributes[] = $attrRow;
            }
    
            $product['attributes'] = $attributes;
            $stmtAttr->close();
        }
        unset($product);
    
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(is_array($products) ? $products : []);
        exit;
    }
    
    elseif (str_starts_with($uri, $baseProductsPath . '/')) {
          $slug = ltrim(substr($uri, strlen($baseProductsPath)), '/');
        
          $stmt = $mysql->prepare("SELECT * FROM products WHERE slug = ?");
          $stmt->bind_param("s", $slug);
          $stmt->execute();
          $result = $stmt->get_result();
        
          $product = $result->fetch_assoc();
        
          if ($product) {
              $stmtAttr = $mysql->prepare("SELECT attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity FROM product_attributes WHERE slug = ?");
              $stmtAttr->bind_param("s", $slug);
              $stmtAttr->execute();
              $resultAttr = $stmtAttr->get_result();
            
              $attributes = [];
              while ($attrRow = $resultAttr->fetch_assoc()) {
                  $attributes[] = $attrRow;
              }
              
              $product['attributes'] = $attributes;
              
              $stmtAttr->close();
          }
          
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($product);
          exit;
      }
    
      
      break;

      case 'POST':
                if ($uri === $baseProductsPath) {
          $action = $_POST['action'] ?? '';
  
          if ($action === 'uploadImage') {
            handleImageUpload();
            return;
          }
  
          if ($action === 'createProduct') {
            handleProductCreate($mysql);
            return;
          }
  
          http_response_code(400);
          echo json_encode(["error" => "Unknown or missing action"]);
          return;
        }

            if (
                 preg_match("#^" . preg_quote($baseProductsPath, '#') . "/category/([^/]+)$#", rtrim($uri, '/'), $matches)
                )
            {
            $categoryName = urldecode($matches[1]);

            $data = json_decode(file_get_contents("php://input"), true);
            $filters = $data['filters'] ?? [];

            $sql = "SELECT * FROM products WHERE category = ?";
            $types = "s";
            $params = [$categoryName];

            $filterIndex = 0;
            foreach ($filters as $attr => $values) {
                if (!is_array($values) || count($values) === 0) continue;

                $filterIndex++;

                // Плейсхолдери
                $placeholders = implode(',', array_fill(0, count($values), '?'));

                // Додаємо OR-групу, де кожен рівень атрибута перевіряється окремо
                $sql .= " AND (
                    EXISTS (
                        SELECT 1 FROM product_attributes pa
                        WHERE pa.slug = products.slug
                        AND pa.attribute_main = ?
                        AND pa.value_main IN ($placeholders)
                    )
                    OR EXISTS (
                        SELECT 1 FROM product_attributes pa
                        WHERE pa.slug = products.slug
                        AND pa.attribute_secondary = ?
                        AND pa.value_secondary IN ($placeholders)
                    )
                    OR EXISTS (
                        SELECT 1 FROM product_attributes pa
                        WHERE pa.slug = products.slug
                        AND pa.attribute_tertiary = ?
                        AND pa.value_tertiary IN ($placeholders)
                    )
                )";

                // Типи: три атрибути і три блоки значень
                $types .= str_repeat("s", 3 + (count($values) * 3));

                // Значення
                $params[] = $attr;              // attribute_main
                foreach ($values as $v) $params[] = $v;

                $params[] = $attr;              // attribute_secondary
                foreach ($values as $v) $params[] = $v;

                $params[] = $attr;              // attribute_tertiary
                foreach ($values as $v) $params[] = $v;
            }


            $stmt = $mysql->prepare($sql);
            if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'DB prepare failed', 'detail' => $mysql->error]);
            exit;
            }

            $bind_names[] = $types;
            foreach ($params as $i => $val) {
            $bind_names[] = &$params[$i];
            }

            call_user_func_array([$stmt, 'bind_param'], $bind_names);
            $stmt->execute();
            $result = $stmt->get_result();

            $products = [];
            while ($row = $result->fetch_assoc()) {
            $products[] = $row;
            }

            foreach ($products as &$product) {
            $slug = $product['slug'];
            $stmtAttr = $mysql->prepare("SELECT attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity FROM product_attributes WHERE slug = ?");
            $stmtAttr->bind_param("s", $slug);
            $stmtAttr->execute();
            $resultAttr = $stmtAttr->get_result();

            $attributes = [];
            while ($attrRow = $resultAttr->fetch_assoc()) {
                $attributes[] = $attrRow;
            }

            $product['attributes'] = $attributes;
            $stmtAttr->close();
            }
            unset($product);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($products);
            exit;
        }
        
        if (str_starts_with($uri, $baseUsersPath . '/login')) {
            handleUserLogin($mysql);
            return;
        }
        break;

        case 'PATCH':
            if (preg_match("#^" . preg_quote($baseProductsPath, '#') . "/([^/]+)$#", $uri, $matches)) {
                $slug = $matches[1];
                $data = json_decode(file_get_contents("php://input"), true);
            
                $quantity = intval($data['quantity'] ?? -1);
                $value_main = $data['value_main'] ?? null;
                $value_secondary = $data['value_secondary'] ?? null;
                $value_tertiary = $data['value_tertiary'] ?? null;
            
                if ($quantity < 0) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Missing or invalid quantity']);
                    return;
                }
            
                // Якщо не передано value_main — оновлюємо загальну кількість товару
                if ($value_main === null) {
                    $stmt = $mysql->prepare("UPDATE products SET quantity = ? WHERE slug = ?");
                    if (!$stmt) {
                        http_response_code(500);
                        echo json_encode(['error' => 'DB prepare failed', 'detail' => $mysql->error]);
                        return;
                    }
            
                    $stmt->bind_param("is", $quantity, $slug);
                    $success = $stmt->execute();
                    $stmt->close();
            
                    if ($success) {
                        echo json_encode(['success' => true]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Update failed']);
                    }
            
                    return;
                }
            
                // Якщо value_main є — оновлюємо конкретну комбінацію
                $sql = "UPDATE product_attributes SET quantity = ? WHERE slug = ? AND value_main = ?";
                $params = [$quantity, $slug, $value_main];
                $types = "iss";
            
                if (empty($value_secondary)) {
                    $sql .= " AND (value_secondary IS NULL OR value_secondary = '')";
                } else {
                    $sql .= " AND value_secondary = ?";
                    $params[] = $value_secondary;
                    $types .= "s";
                }
            
                if (empty($value_tertiary)) {
                    $sql .= " AND (value_tertiary IS NULL OR value_tertiary = '')";
                } else {
                    $sql .= " AND value_tertiary = ?";
                    $params[] = $value_tertiary;
                    $types .= "s";
                }
            
                $stmt = $mysql->prepare($sql);
                if (!$stmt) {
                    http_response_code(500);
                    echo json_encode(['error' => 'DB prepare failed', 'detail' => $mysql->error]);
                    return;
                }
            
                $bind_names[] = $types;
                foreach ($params as $key => $value) {
                    $bind_names[] = &$params[$key];
                }
                call_user_func_array([$stmt, 'bind_param'], $bind_names);
            
                $success = $stmt->execute();
                $stmt->close();
            
                if ($success) {
                    echo json_encode(['success' => true]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Update failed']);
                }
            
                return;
            }
            break;
    

          case 'DELETE':
            if (preg_match("#^" . preg_quote($baseProductsPath, '#') . "/([^/]+)$#", $uri, $matches)) {
                $slug = $matches[1];
          
                $stmtAttr = $mysql->prepare("DELETE FROM product_attributes WHERE slug = ?");
                if (!$stmtAttr) {
                    http_response_code(500);
                    echo json_encode(['error' => 'DB prepare failed (attributes)', 'detail' => $mysql->error]);
                    return;
                }
                $stmtAttr->bind_param("s", $slug);
                $stmtAttr->execute();
                $stmtAttr->close();
          
                $stmtProd = $mysql->prepare("DELETE FROM products WHERE slug = ?");
                if (!$stmtProd) {
                    http_response_code(500);
                    echo json_encode(['error' => 'DB prepare failed (product)', 'detail' => $mysql->error]);
                    return;
                }
                $stmtProd->bind_param("s", $slug);
                $stmtProd->execute();
                $stmtProd->close();
          
                echo json_encode(['success' => true, 'slug' => $slug]);
                return;
            }
            break;
          

  http_response_code(404);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(["error" => "Route not found"]);
  exit;
  };
}

function handleProductCreate(mysqli $mysql): void {
    header('Content-Type: application/json; charset=utf-8');
  
    $name = $_POST['name'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $newProduct = ($_POST['newProduct'] ?? 'false') === 'true' ? 1 : 0;
    $popularProduct = ($_POST['popularProduct'] ?? 'false') === 'true' ? 1 : 0;
    $slug = $_POST['slug'] ?? '';
    $imageUrl = $_POST['imageUrl'] ?? '';
    $attributesRaw = $_POST['attributes'] ?? '';
  
    $stmt = $mysql->prepare("INSERT INTO products (name, slug, price, quantity, image, category, newProduct, popularProduct, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
      http_response_code(500);
      echo json_encode(["error" => "DB prepare failed", "detail" => $mysql->error]);
      return;
    }
  
    $stmt->bind_param("ssdissiis", $name, $slug, $price, $quantity, $imageUrl, $category, $newProduct, $popularProduct, $description);
    $stmt->execute();
    $productId = $stmt->insert_id;
    $stmt->close();
  
    $attributes = json_decode($attributesRaw, true);
  
    if (!isset($attributes['attributeNames'], $attributes['attributeValues'], $attributes['variants'])) {
      echo json_encode(["success" => true, "productId" => $productId, "note" => "No attributes provided"]);
      return;
    }
  
    $attributeNames = $attributes['attributeNames'];
    $attributeValues = $attributes['attributeValues'];
    $variants = $attributes['variants'];
  
    $stmt = $mysql->prepare("INSERT INTO product_attributes (slug, attribute_main, value_main, attribute_secondary, value_secondary, attribute_tertiary, value_tertiary, extraPrice, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
      http_response_code(500);
      echo json_encode(["error" => "DB prepare failed (attributes)", "detail" => $mysql->error]);
      return;
    }
  
    foreach ($variants as $variant) {
      $combo = $variant['combination'] ?? [];
      $extraPrice = floatval($variant['price'] ?? 0);
      $qty = intval($variant['quantity'] ?? 0);
  
      $mainAttr = $attributeNames[0] ?? null;
      $secAttr = $attributeNames[1] ?? null;
      $thirdAttr = $attributeNames[2] ?? null;
        
      $valMain = $mainAttr ? ($combo[$mainAttr] ?? null) : null;
      $valSec = $secAttr ? ($combo[$secAttr] ?? null) : null;
      $valThird = $thirdAttr ? ($combo[$thirdAttr] ?? null) : null;
  
$mainAttrVal = $mainAttr ?? '';
$valMainVal = $valMain ?? '';
$secAttrVal = $secAttr ?? '';
$valSecVal = $valSec ?? '';
$thirdAttrVal = $thirdAttr ?? '';
$valThirdVal = $valThird ?? '';
$extraPriceVal = $extraPrice;
$qtyVal = $qty;

$stmt->bind_param(
    "ssssssssi",
    $slug,
    $mainAttrVal,
    $valMainVal,
    $secAttrVal,
    $valSecVal,
    $thirdAttrVal,
    $valThirdVal,
    $extraPriceVal,
    $qtyVal
);


          $stmt->execute();
    }
  
    $stmt->close();
  
    echo json_encode(["success" => true, "productId" => $productId]);
  }
  

function handleImageUpload(): void {
    header('Content-Type: application/json; charset=utf-8');
  
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      http_response_code(400);
      echo json_encode(["error" => "Image is missing or upload failed"]);
      return;
    }
  
    $uploadDir = __DIR__ . '/api/v1/uploads/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
  
    $uniqueName = uniqid() . '_' . basename($_FILES['image']['name']);
    $imagePath = $uploadDir . $uniqueName;
  
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
      http_response_code(500);
      echo json_encode(["error" => "Failed to move uploaded image"]);
      return;
    }
  
    $imageUrl = '/uploads/' . $uniqueName;
    echo json_encode(["success" => true, "imageUrl" => $imageUrl]);
}

function loadEnv($path = __DIR__ . '/.env') {
    if (!file_exists($path)) return;
  
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      if (strpos(trim($line), '#') === 0) continue;
  
      list($name, $value) = explode('=', $line, 2);
      $_ENV[trim($name)] = trim($value);
    }
}

function handleUserLogin(mysqli $mysql): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';


    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required']);
        return;
    }

    $stmt = $mysql->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid email or password']);
        $stmt->close();
        return;
    }

    $stmt->bind_result($userId, $passwordHash);
    $stmt->fetch();
    $stmt->close();



    if (!password_verify($password, $passwordHash)) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid email or password']);
        return;
    }

    // (опціонально) генерація JWT токена
    $token = base64_encode(bin2hex(random_bytes(32))); // простий токен
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'userId' => $userId,
        'token' => $token
    ]);
}
