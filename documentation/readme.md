# Form&System API

aglio -i documentation/documentation.apib -o documentation/output.html --theme-variables streak --theme-template triple

## Endpoints
### Collections

### Fragments
Relationships | inverse Relationships
--- | ---
- | ownedByPages
collections | ownedByCollections
fragments | ownedByFragments
metadetails | -
images | -
videos | -
files | -

### Images
Relationships | inverse Relationships
--- | ---
images | ownedByImages
metadetails | ownedByMetadetails
- | ownedByCollections
- | ownedByFragments
- | ownedByVideos
- | ownedByFiles
tags | -

### Metadetails
Relationships | inverse Relationships
--- | ---
images | ownedByImages
- | ownedByFragments
- | ownedByPages
- | ownedByVideos
- | ownedByFiles

### Pages
Relationships | inverse Relationships
--- | ---
pages | ownedByPages
fragments | -
collections | ownedByCollections
metadetails | -
tags | -

### Tags (not implemented)
Relationships | inverse Relationships
--- | ---
- | ownedByCollections
pages | -
images | -
videos | -
files | -

### Videos (not implemented)
Relationships | inverse Relationships
--- | ---
- | ownedByCollections
- | ownedByFragments
images | -
tags | -
### Files (not implemented)
Relationships | inverse Relationships
--- | ---
- | ownedByCollections
- | ownedByFragments
images | -
tags | -
