resource "aws_dynamodb_table" "candidate_questions" {
  name         = "${var.app_name}-candidate-questions-${var.environment}"
  billing_mode = "PAY_PER_REQUEST"
  hash_key     = "id"
  range_key    = "created_at"

  attribute {
    name = "id"
    type = "S"
  }

  attribute {
    name = "created_at"
    type = "S"
  }

  attribute {
    name = "candidate_id"
    type = "S"
  }

  attribute {
    name = "topic"
    type = "S"
  }

  global_secondary_index {
    name            = "candidate-id-index"
    hash_key        = "candidate_id"
    range_key       = "created_at"
    projection_type = "ALL"
  }

  global_secondary_index {
    name            = "topic-created_at-index"
    hash_key        = "topic"
    range_key       = "created_at"
    projection_type = "ALL"
  }

  tags = {
    Environment = var.environment
    App         = var.app_name
  }
}
