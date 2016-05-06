# Form&System API

## Endpoints
### Collections
Relationships | inverse Relationships
-- | --
pages | ownedByPages
collections | ownedByCollections
fragments | ownedByFragments
images | -
videos | -
files | -
tags | -

### Fragments
Relationships | inverse Relationships
 | ownedByPages
 -- | --
collections | ownedByCollections
fragments | ownedByFragments
metadetails | -
images | -
videos | -
files | -

### Images
Relationships | inverse Relationships
-- | --
images | ownedByImages
 | ownedByCollections
 | ownedByFragments
metadetails | ownedByMetadetails
