data "aws_iam_role" "lambda_layer" {
  name = "bref-layer-php-82-fpm"
  count = 0
}

resource "aws_lambda_function" "api" {
  function_name = "${var.app_name}-api-${var.environment}"
  filename      = "${path.module}/../deploy.zip"
  handler       = "handler.handler"
  runtime       = "provided.al2023"
  timeout       = 29
  memory_size   = 1024
  role          = aws_iam_role.lambda_exec.arn

  layers = [
    "arn:aws:lambda:${var.aws_region}:209497400698:layer:php-82-fpm:50"
  ]

  vpc_config {
    subnet_ids         = var.subnet_ids
    security_group_ids = [var.security_group_id]
  }

  environment {
    variables = {
      APP_ENV        = "production"
      APP_DEBUG      = "false"
      APP_NAME       = "InterviewApp"
      MONGODB_URI    = var.mongodb_uri
      FRONTEND_URL   = "https://${var.frontend_url}"
      SESSION_DRIVER = "file"
      CACHE_STORE    = "file"
      QUEUE_CONNECTION = "sync"
    }
  }

  source_code_hash = filebase64sha256("${path.module}/../deploy.zip")

  tags = {
    Environment = var.environment
    App         = var.app_name
  }
}

resource "aws_cloudwatch_log_group" "lambda_logs" {
  name              = "/aws/lambda/${aws_lambda_function.api.function_name}"
  retention_in_days = 14

  tags = {
    Environment = var.environment
    App         = var.app_name
  }
}
