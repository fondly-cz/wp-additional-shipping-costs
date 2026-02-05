#!/bin/bash

# Check if a version number was provided
if [ -z "$1" ]; then
  echo "❌ Error: Please provide a version number."
  echo "Usage: ./release.sh <version>"
  echo "Example: ./release.sh 1.1"
  exit 1
fi

VERSION=$1

# 1. Update the version in the main PHP file
echo "📝 Updating version to $VERSION in wp-additional-shipping-costs.php..."
sed -i "s/Version: .*/Version: $VERSION/" wp-additional-shipping-costs.php

# 2. Add the file to git
git add wp-additional-shipping-costs.php

# 3. Commit the change
echo "💾 Committing version bump..."
git commit -m ":bookmark: Bump version to $VERSION"

# 4. Create the tag
echo "🏷️  Creating tag v$VERSION..."
git tag "v$VERSION"

# 5. Push to GitHub
echo "🚀 Pushing to GitHub..."
git push origin main --tags

echo "✅ Done! Release v$VERSION has been pushed and the build workflow should start shortly."
